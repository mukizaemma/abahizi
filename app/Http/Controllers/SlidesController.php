<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Slide;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Slide::latest()->get();
        $setting = Setting::firstOrEmpty();

        return view('admin.slides', [
            'slides' => $slides,
            'setting' => $setting,
        ]);
    }

    public function saveHero(Request $request)
    {
        $request->validate([
            'hero_media_type' => ['required', 'in:slideshow,image,video'],
            'hero_headline' => ['nullable', 'string', 'max:255'],
            'hero_subheadline' => ['nullable', 'string', 'max:500'],
            'hero_video_url' => ['nullable', 'string', 'max:2048'],
            'hero_poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'hero_banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'hero_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg', 'max:51200'],
        ]);

        $data = Setting::first();
        if (! $data) {
            $data = new Setting();
            $data->title = 'Company Name';
            $data->save();
            $data = Setting::first();
        }

        $data->hero_headline = $request->input('hero_headline');
        $data->hero_subheadline = $request->input('hero_subheadline');

        if (Schema::hasColumn('settings', 'hero_media_type')) {
            $data->hero_media_type = $request->input('hero_media_type');
        }

        if ($request->hasFile('hero_banner')) {
            $path = $request->file('hero_banner')->store('images/hero', 'public');
            $data->hero_poster = $path;
        }

        if ($request->input('hero_media_type') === 'video') {
            if ($request->filled('hero_video_url')) {
                $data->hero_video_url = trim((string) $request->input('hero_video_url'));
            }
            if ($request->hasFile('hero_video')) {
                $path = $request->file('hero_video')->store('videos/hero', 'public');
                $data->hero_video_url = $path;
            }
        }

        if ($request->hasFile('hero_poster')) {
            $path = $request->file('hero_poster')->store('images/hero', 'public');
            $data->hero_poster = $path;
        }

        $data->save();

        return redirect()->route('slides')->with('success', 'Homepage hero has been updated.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);
        $data = new Slide();
        $data->heading = trim((string) $request->input('heading', ''));
        $data->subheading = '';
    
        if ($request->hasFile('image')) {
            $data->image = $request->file('image')->store('images/slides', 'public');
        }
    
        $stored = $data->save();
    
        if ($stored) {
            return redirect('slides')->with('success', 'New Image has been added successfully');
        }
    
        return redirect()->back()->with('error', 'Failed to add new Image');
    }
    
    public function edit($id)
    {
        $data = Slide::findOrFail($id);
        return view('admin.slideUpdate', ['data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);
        $data = Slide::findOrFail($id);
        $data->heading = trim((string) $request->input('heading', ''));

        if ($request->hasFile('image')) {
            if (!empty($data->image) && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }
            $data->image = $request->file('image')->store('images/slides', 'public');
        }

        $data->save();

        return redirect('slides')->with('success','Image has been updated');
    }

    public function destroy($id)
    {
        $image = Slide::findOrFail($id);
        // delete the image file
        if (!empty($image->image) && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        $image->delete();
        return redirect()->back()->with('warning', 'Item has been deleted');
    }
}
