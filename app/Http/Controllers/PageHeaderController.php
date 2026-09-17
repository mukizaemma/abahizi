<?php

namespace App\Http\Controllers;

use App\Support\PageHeaderService;
use Illuminate\Http\Request;

class PageHeaderController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'page_header_key' => ['required', 'string'],
            'header_title' => ['nullable', 'string', 'max:255'],
            'header_caption' => ['nullable', 'string', 'max:2000'],
            'header_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        PageHeaderService::saveFromRequest($request);

        return redirect()->back()->with('success', 'Page header has been saved.');
    }
}
