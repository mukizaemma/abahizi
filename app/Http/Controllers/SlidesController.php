<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Slide;

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
        return view('admin.slides', ['slides'=>$slides]);
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
        $data->heading = $request->input('heading', 'Default Heading');
        $data->subheading = "Abahizi Rwanda";
    
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
        $data->heading = $request->input('heading');
        //$data->subheading = $request->input('subheading');

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
