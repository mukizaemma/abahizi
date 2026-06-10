<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        $team = Team::query()->latest()->get();

        return view('admin.team', ['team' => $team]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'email' => ['nullable', 'email', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:500'],
            'linkedin' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string'],
            'display' => ['nullable', 'in:Yes,No'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $fileName = $this->storeStaffImage($request->file('image'));

        Team::create([
            'names' => $validated['names'],
            'position' => $validated['position'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'linkedin' => $validated['linkedin'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'category' => $validated['category'] ?? null,
            'display' => $validated['display'] ?? 'Yes',
            'slug' => $this->uniqueSlug($validated['names']),
            'image' => $fileName,
        ]);

        return redirect()->route('staff')->with('success', 'Team member added successfully.');
    }

    public function edit($id)
    {
        $data = Team::findOrFail($id);

        return view('admin.teamUpdate', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $data = Team::findOrFail($id);

        $validated = $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'email' => ['nullable', 'email', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:500'],
            'linkedin' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string'],
            'display' => ['nullable', 'in:Yes,No'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $data->names = $validated['names'];
        $data->position = $validated['position'];
        $data->phone = $validated['phone'] ?? null;
        $data->email = $validated['email'] ?? null;
        $data->facebook = $validated['facebook'] ?? null;
        $data->instagram = $validated['instagram'] ?? null;
        $data->linkedin = $validated['linkedin'] ?? null;
        $data->bio = $validated['bio'] ?? null;
        $data->category = $validated['category'] ?? null;
        $data->display = $validated['display'] ?? $data->display ?? 'Yes';

        if ($data->slug === null || $data->slug === '') {
            $data->slug = $this->uniqueSlug($validated['names'], $data->id);
        }

        if ($request->hasFile('image')) {
            $this->deleteStaffImage($data->image);
            $data->image = $this->storeStaffImage($request->file('image'));
        }

        $data->save();

        return redirect()->route('staff')->with('success', 'Team member updated successfully.');
    }

    public function destroy($id)
    {
        $data = Team::findOrFail($id);
        $this->deleteStaffImage($data->image);
        $data->delete();

        return redirect()->route('staff')->with('success', 'Team member removed successfully.');
    }

    private function storeStaffImage($file): string
    {
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->storeAs('public/images/staff', $fileName);

        return $fileName;
    }

    private function deleteStaffImage(?string $image): void
    {
        if ($image === null || $image === '') {
            return;
        }

        $path = 'images/staff/' . ltrim($image, '/');
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'team-member';
        $slug = $base;
        $i = 1;

        while (
            Team::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
