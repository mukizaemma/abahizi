<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Programimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index()
    {
        $data = Program::query()
            ->withCount('activities')
            ->latest()
            ->get();
        return view('admin.programs', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
        ]);

        $program = new Program();
        $program->title = $request->input('title');
        $program->description = $request->input('description');
        $program->slug = $this->uniqueSlug($request->input('title'));
        $program->image = $request->file('image')->store('images/programs', 'public');
        if (Schema::hasColumn('programs', 'added_by')) {
            $program->added_by = Auth::id() ?? Auth::guard('admin')->id();
        }
        $program->save();

        if (Schema::hasTable('programimages')) {
            foreach ($request->file('gallery_images', []) as $galleryImage) {
                $path = $galleryImage->store('images/programs/gallery', 'public');
                Programimage::create([
                    'image' => $path,
                    'program_id' => $program->id,
                    'added_by' => $request->user()->id,
                ]);
            }
        }

        return redirect()->route('programs')->with('success', 'Program added successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Program::find($id);
        if (!$data) {
            return redirect()->route('programs')->with('error', 'Program not found.');
        }
        $images = Schema::hasTable('programimages')
            ? $data->images()->latest()->get()
            : collect();
        return view('admin.programUpdate', [
            'data' => $data,
            'images' => $images,
            'totalImages' => $images->count(),
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
        ]);

        $data = Program::findOrFail($id);
        $data->title = $request->input('title');
        $data->description = $request->input('description');
        if ($data->slug !== Str::slug($request->input('title'))) {
            $data->slug = $this->uniqueSlug($request->input('title'), $data->id);
        }

        if ($request->hasFile('image')) {
            if (!empty($data->image) && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }
            $data->image = $request->file('image')->store('images/programs', 'public');
        }

        $data->save();

        return redirect()->route('editProgram', $data->id)->with('success', 'Program has been updated');
    }

    public function destroy($id)
    {
        $data = Program::findOrFail($id);
        $isSuperAdmin = (Auth::user()->email ?? null) === 'admin@iremetech.com';
        $isOwner = !Schema::hasColumn('programs', 'added_by')
            || ((int) ($data->added_by ?? 0) === (int) (Auth::id() ?? Auth::guard('admin')->id()));
        if (! $isSuperAdmin && ! $isOwner) {
            return redirect()->back()->with('error', 'You can only delete programs that you created.');
        }
        if (Schema::hasTable('programimages')) {
            foreach ($data->images as $img) {
                if (! empty($img->image) && Storage::disk('public')->exists($img->image)) {
                    Storage::disk('public')->delete($img->image);
                }
                $img->delete();
            }
        }
        foreach ($data->activities as $activity) {
            if (! empty($activity->image) && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            foreach ($activity->images as $projectImage) {
                if (! empty($projectImage->image) && Storage::disk('public')->exists($projectImage->image)) {
                    Storage::disk('public')->delete($projectImage->image);
                }
                $projectImage->delete();
            }
            $activity->delete();
        }
        if (!empty($data->image) && Storage::disk('public')->exists($data->image)) {
            Storage::disk('public')->delete($data->image);
        }
        $data->delete();
        return redirect()->route('programs')->with('success', 'Program deleted successfully');
    }

    public function addProgramImage(Request $request)
    {
        if (!Schema::hasTable('programimages')) {
            return redirect()->back()->with('error', 'Program gallery table is missing. Please run migrations first.');
        }

        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $files = $request->file('gallery_images', []);
        if (empty($files)) {
            $files = $request->file('image', []);
        }

        if (empty($files)) {
            return redirect()->back()->with('error', 'Please select at least one image to upload.');
        }

        $userId = Auth::id() ?? Auth::guard('admin')->id();
        if (!$userId) {
            return redirect()->back()->with('error', 'Unable to determine current user for upload.');
        }

        foreach ($files as $image) {
            $path = $image->store('images/programs/gallery', 'public');

            Programimage::create([
                'image' => $path,
                'program_id' => $request->program_id,
                'added_by' => $userId,
            ]);
        }

        return redirect()->back()->with('success', 'Program gallery images uploaded successfully!');
    }

    public function deleteProgramImage($id)
    {
        $image = Programimage::findOrFail($id);

        if (!empty($image->image) && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return redirect()->back()->with('warning', 'Image has been deleted');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $i = 1;

        while (
            Program::query()
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
