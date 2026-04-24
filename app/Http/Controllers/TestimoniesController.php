<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Testimony;

use Illuminate\Http\Request;

class TestimoniesController extends Controller
{
    public function index()
    {

        $data = DB::table('testimonies')->latest()->get();
        return view('admin.testimonies', ['data'=>$data]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'testimony' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);
        $data = new Testimony();
        $data->names = $request->names;
        $data ->title = $request->title;
        $data ->testimony = $request->testimony;

        // Uploading image
        if ($request->hasFile('image')) {
            $data->image = $request->file('image')->store('images/testimonies', 'public');
        }

        $stored = $data->save();

        if($stored){
            return redirect()->route('getTestimonials')->with('success', 'New testimony has been added successfully');
        }

        return redirect()->back()->with('error','Failed to add new Testimony');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Testimony::findOrFail($id);
        return view('admin.testimonyUpdate', ['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'testimony' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $data = Testimony::findOrFail($id);
        $data->names = $request->input('names');
        $data->title = $request->input('title');
        $data->testimony = $request->input('testimony');

        if ($request->hasFile('image')) {
            if (!empty($data->image) && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }
            $data->image = $request->file('image')->store('images/testimonies', 'public');
        }

        $data->save();

        return redirect()->route('getTestimonials')->with('success','Testimony has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Testimony::findOrFail($id);
        if (!empty($data->image) && Storage::disk('public')->exists($data->image)) {
            Storage::disk('public')->delete($data->image);
        }
        $data->delete();
        return redirect()->back()->with('success', 'Item has been deleted');
    }
}
