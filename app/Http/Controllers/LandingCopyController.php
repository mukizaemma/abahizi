<?php

namespace App\Http\Controllers;

use App\Support\SiteCopy;
use Illuminate\Http\Request;

class LandingCopyController extends Controller
{
    public function update(Request $request)
    {
        SiteCopy::saveFromRequest($request);

        return redirect()->back()->with('success', 'Section titles have been saved.');
    }
}
