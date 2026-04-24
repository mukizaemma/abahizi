<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Background;
use App\Models\Homepage;

class BackgroundController extends Controller
{
    public function background(){
        $data = background::first();
        if($data===null)
        {
            $data = new background();
            $data->description = 'Our Background';
            $data->save();
            $data = background::first();
        }

        return view('admin.background', ['data'=>$data]);
    }

public function saveBackg(Request $request)
{
    $request->validate([
        'description' => 'nullable|string',
        'donations' => 'nullable|string',
        'approach_content' => 'nullable|string',
        'model_content' => 'nullable|string',
        'problem_statement' => 'nullable|string',
        'solution_statement' => 'nullable|string',
        'what_we_do' => 'nullable|string',
        'how_it_works' => 'nullable|string',
        'expertise_content' => 'nullable|string',
        'manufacturing_impact_content' => 'nullable|string',
        'products_intro' => 'nullable|string',
        'factory_description' => 'nullable|string',
        'factory_services' => 'nullable|string',
        'factory_community_impact' => 'nullable|string',
        'factory_training_facilities' => 'nullable|string',
        'factory_services_subitems' => 'nullable|string',
        'factory_community_impact_subitems' => 'nullable|string',
        'factory_training_facilities_subitems' => 'nullable|string',
        'families_impacted' => 'nullable|string|max:255',
        'jobs_created' => 'nullable|string|max:255',
        'training_hours' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'core_values_background' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        'model_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        'factory_services_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        'factory_community_impact_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        'factory_training_facilities_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ]);

    $data = Background::firstOrEmpty();
    if ($request->has('description')) {
        $data->description = $request->input('description');
    }
    if ($request->has('donations')) {
        $data->donations = $request->input('donations');
    }
    if (Schema::hasColumn('backgrounds', 'approach_content') && $request->has('approach_content')) {
        $data->approach_content = $request->input('approach_content');
    }
    if (Schema::hasColumn('backgrounds', 'model_content') && $request->has('model_content')) {
        $data->model_content = $request->input('model_content');
    }
    if (Schema::hasColumn('backgrounds', 'problem_statement') && $request->has('problem_statement')) {
        $data->problem_statement = $request->input('problem_statement');
    }
    if (Schema::hasColumn('backgrounds', 'solution_statement') && $request->has('solution_statement')) {
        $data->solution_statement = $request->input('solution_statement');
    }
    if (Schema::hasColumn('backgrounds', 'what_we_do') && $request->has('what_we_do')) {
        $data->what_we_do = $request->input('what_we_do');
    }
    if (Schema::hasColumn('backgrounds', 'how_it_works') && $request->has('how_it_works')) {
        $data->how_it_works = $request->input('how_it_works');
    }
    if (Schema::hasColumn('backgrounds', 'expertise_content') && $request->has('expertise_content')) {
        $data->expertise_content = $request->input('expertise_content');
    }
    if (Schema::hasColumn('backgrounds', 'manufacturing_impact_content') && $request->has('manufacturing_impact_content')) {
        $data->manufacturing_impact_content = $request->input('manufacturing_impact_content');
    }
    if (Schema::hasColumn('backgrounds', 'products_intro') && $request->has('products_intro')) {
        $data->products_intro = $request->input('products_intro');
    }
    if (Schema::hasColumn('backgrounds', 'factory_description') && $request->has('factory_description')) {
        $data->factory_description = $request->input('factory_description');
    }
    if (Schema::hasColumn('backgrounds', 'factory_services') && $request->has('factory_services')) {
        $data->factory_services = $request->input('factory_services');
    }
    if (Schema::hasColumn('backgrounds', 'factory_community_impact') && $request->has('factory_community_impact')) {
        $data->factory_community_impact = $request->input('factory_community_impact');
    }
    if (Schema::hasColumn('backgrounds', 'factory_training_facilities') && $request->has('factory_training_facilities')) {
        $data->factory_training_facilities = $request->input('factory_training_facilities');
    }
    if (Schema::hasColumn('backgrounds', 'factory_services_subitems') && $request->has('factory_services_subitems')) {
        $data->factory_services_subitems = $request->input('factory_services_subitems');
    }
    if (Schema::hasColumn('backgrounds', 'factory_community_impact_subitems') && $request->has('factory_community_impact_subitems')) {
        $data->factory_community_impact_subitems = $request->input('factory_community_impact_subitems');
    }
    if (Schema::hasColumn('backgrounds', 'factory_training_facilities_subitems') && $request->has('factory_training_facilities_subitems')) {
        $data->factory_training_facilities_subitems = $request->input('factory_training_facilities_subitems');
    }
    if (Schema::hasColumn('backgrounds', 'families_impacted') && $request->has('families_impacted')) {
        $data->families_impacted = $request->input('families_impacted');
    }
    if (Schema::hasColumn('backgrounds', 'jobs_created') && $request->has('jobs_created')) {
        $data->jobs_created = $request->input('jobs_created');
    }
    if (Schema::hasColumn('backgrounds', 'training_hours') && $request->has('training_hours')) {
        $data->training_hours = $request->input('training_hours');
    }

    // Process image
    if ($request->hasFile('image')) {
        if ($data->image && Storage::disk('public')->exists('images/' . $data->image)) {
            Storage::disk('public')->delete('images/' . $data->image);
        }

        $filename = 'bg_' . time() . '_' . Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('images', $filename, 'public');
        $data->image = $filename;
    }

    // Process image1
    if ($request->hasFile('image1')) {
        if ($data->image1 && Storage::disk('public')->exists('images/' . $data->image1)) {
            Storage::disk('public')->delete('images/' . $data->image1);
        }

        $filename1 = 'img1_' . time() . '_' . Str::random(5) . '.' . $request->file('image1')->getClientOriginalExtension();
        $request->file('image1')->storeAs('images', $filename1, 'public');
        $data->image1 = $filename1;
    }

    // Process image2
    if ($request->hasFile('image2')) {
        if ($data->image2 && Storage::disk('public')->exists('images/' . $data->image2)) {
            Storage::disk('public')->delete('images/' . $data->image2);
        }

        $filename2 = 'img2_' . time() . '_' . Str::random(5) . '.' . $request->file('image2')->getClientOriginalExtension();
        $request->file('image2')->storeAs('images', $filename2, 'public');
        $data->image2 = $filename2;
    }

    // Core values section background (About page parallax)
    if (Schema::hasColumn('backgrounds', 'core_values_background') && $request->hasFile('core_values_background')) {
        if ($data->core_values_background && Storage::disk('public')->exists('images/' . $data->core_values_background)) {
            Storage::disk('public')->delete('images/' . $data->core_values_background);
        }

        $cvFilename = 'cv_bg_' . time() . '_' . Str::random(5) . '.' . $request->file('core_values_background')->getClientOriginalExtension();
        $request->file('core_values_background')->storeAs('images', $cvFilename, 'public');
        $data->core_values_background = $cvFilename;
    }

    // Process model image
    if (Schema::hasColumn('backgrounds', 'model_image') && $request->hasFile('model_image')) {
        if ($data->model_image && Storage::disk('public')->exists('images/' . $data->model_image)) {
            Storage::disk('public')->delete('images/' . $data->model_image);
        }

        $modelFilename = 'model_' . time() . '_' . Str::random(5) . '.' . $request->file('model_image')->getClientOriginalExtension();
        $request->file('model_image')->storeAs('images', $modelFilename, 'public');
        $data->model_image = $modelFilename;
    }

    if (Schema::hasColumn('backgrounds', 'factory_services_image') && $request->hasFile('factory_services_image')) {
        if ($data->factory_services_image && Storage::disk('public')->exists('images/' . $data->factory_services_image)) {
            Storage::disk('public')->delete('images/' . $data->factory_services_image);
        }
        $filename = 'factory_services_' . time() . '_' . Str::random(5) . '.' . $request->file('factory_services_image')->getClientOriginalExtension();
        $request->file('factory_services_image')->storeAs('images', $filename, 'public');
        $data->factory_services_image = $filename;
    }

    if (Schema::hasColumn('backgrounds', 'factory_community_impact_image') && $request->hasFile('factory_community_impact_image')) {
        if ($data->factory_community_impact_image && Storage::disk('public')->exists('images/' . $data->factory_community_impact_image)) {
            Storage::disk('public')->delete('images/' . $data->factory_community_impact_image);
        }
        $filename = 'factory_impact_' . time() . '_' . Str::random(5) . '.' . $request->file('factory_community_impact_image')->getClientOriginalExtension();
        $request->file('factory_community_impact_image')->storeAs('images', $filename, 'public');
        $data->factory_community_impact_image = $filename;
    }

    if (Schema::hasColumn('backgrounds', 'factory_training_facilities_image') && $request->hasFile('factory_training_facilities_image')) {
        if ($data->factory_training_facilities_image && Storage::disk('public')->exists('images/' . $data->factory_training_facilities_image)) {
            Storage::disk('public')->delete('images/' . $data->factory_training_facilities_image);
        }
        $filename = 'factory_training_' . time() . '_' . Str::random(5) . '.' . $request->file('factory_training_facilities_image')->getClientOriginalExtension();
        $request->file('factory_training_facilities_image')->storeAs('images', $filename, 'public');
        $data->factory_training_facilities_image = $filename;
    }

    $data->save();

    return redirect()->back()->with('success', 'Background has been updated successfully');
}




}
