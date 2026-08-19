<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\News;
use App\Models\Blogimages;
use Illuminate\Http\RedirectResponse;

class NewsController extends Controller
{
    public function index()
    {
        $blogs = News::withCount('blogimages')->latest()->paginate(10);
        return view('admin.news', [
            'blogs' => $blogs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'status' => ['nullable', 'in:draft,publish'],
        ]);

        $fileName = '';
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->store('images/news', 'public');
        }

        $publishNow = $request->input('status') === 'publish';

        $blog = new News();
        $blog->title = $request->input('title');
        $blog->author = $request->input('author');
        $blog->body = $request->input('body');
        $blog->image = $fileName ?: null;
        $blog->slug = $this->uniqueSlug($request->input('title'));
        $blog->published_at = $publishNow ? now() : null;
        $blog->published_by = $publishNow ? (Auth::id() ?? Auth::guard('admin')->id()) : null;
        if (Schema::hasColumn('news', 'added_by')) {
            $blog->added_by = Auth::id() ?? Auth::guard('admin')->id();
        }
        $blog->save();

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $gallery) {
                $path = $gallery->store('images/news/gallery', 'public');
                $blog->blogimages()->create([
                    'gallery' => $path,
                    'news_id' => $blog->id,
                ]);
            }
        }

        $message = $publishNow
            ? 'Update published successfully.'
            : 'Update saved as draft.';

        return redirect()->route('blog.index')->with('success', $message);
    }

    public function edit($id)
    {
        $blog = News::with('blogimages')->findOrFail($id);
        return view('admin.newsUpdate', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'status' => ['nullable', 'in:draft,publish'],
        ]);

        $blog = News::with('blogimages')->findOrFail($id);

        if ($request->hasFile('image')) {
            if (! empty($blog->image) && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('images/news', 'public');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $gallery) {
                $path = $gallery->store('images/news/gallery', 'public');
                $blog->blogimages()->create([
                    'gallery' => $path,
                    'news_id' => $blog->id,
                ]);
            }
        }

        $blog->title = $request->input('title');
        $blog->author = $request->input('author');
        $blog->body = $request->input('body');
        $blog->slug = $this->uniqueSlug($request->input('title'), $blog->id);

        if ($request->input('status') === 'publish') {
            $blog->published_at = $blog->published_at ?: now();
            $blog->published_by = $blog->published_by ?: (Auth::id() ?? Auth::guard('admin')->id());
        } elseif ($request->input('status') === 'draft') {
            $blog->published_at = null;
            $blog->published_by = null;
        }

        $blog->save();

        $message = $request->input('status') === 'publish'
            ? 'Update published successfully.'
            : ($request->input('status') === 'draft'
                ? 'Update saved as draft.'
                : 'Update saved successfully.');

        return redirect()->route('blog.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $blog = News::findOrFail($id);
        $isSuperAdmin = (Auth::user()->email ?? null) === 'admin@iremetech.com';
        $isOwner = ! Schema::hasColumn('news', 'added_by')
            || ((int) ($blog->added_by ?? 0) === (int) (Auth::id() ?? Auth::guard('admin')->id()));
        if (! $isSuperAdmin && ! $isOwner) {
            return redirect()->back()->with('error', 'You can only delete updates that you created.');
        }
        $galleries = $blog->blogimages;
        if (! empty($blog->image) && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        foreach ($galleries as $gallery) {
            if (! empty($gallery->gallery) && Storage::disk('public')->exists($gallery->gallery)) {
                Storage::disk('public')->delete($gallery->gallery);
            }
        }
        $blog->blogimages()->delete();
        $blog->delete();

        return back()->with('success', 'Update deleted successfully.');
    }

    public function publish(News $blog): RedirectResponse
    {
        $blog->published_at = now();
        $blog->published_by = auth()->id();
        $blog->save();

        return back()->with('success', 'Update published successfully.');
    }

    public function unpublish(News $blog): RedirectResponse
    {
        $blog->published_at = null;
        $blog->published_by = null;
        $blog->save();

        return back()->with('warning', 'Update moved back to draft.');
    }

    public function deleteBlogImage($id): RedirectResponse
    {
        $image = Blogimages::findOrFail($id);
        if (! empty($image->gallery) && Storage::disk('public')->exists($image->gallery)) {
            Storage::disk('public')->delete($image->gallery);
        }
        $image->delete();
        return back()->with('warning', 'Gallery image deleted.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $i = 1;
        while (
            News::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }
        return $slug;
    }
}
