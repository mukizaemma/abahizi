<?php

namespace App\Http\Controllers;

use App\Support\AdminGuide;

class AdminGuideController extends Controller
{
    public function show(?string $slug = null)
    {
        if ($slug === null || $slug === '') {
            return redirect()->route('admin.guide.show', ['slug' => AdminGuide::firstSlug()]);
        }

        $nav = AdminGuide::navigation($slug);
        if (($nav['current']['slug'] ?? '') !== $slug) {
            return redirect()->route('admin.guide.show', ['slug' => AdminGuide::firstSlug()]);
        }

        return view('admin.guide.show', [
            'nav' => $nav,
        ]);
    }
}
