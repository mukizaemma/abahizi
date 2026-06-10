<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        $team = Team::query()->orderedForDisplay()->get();

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
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'created_at' => ['nullable', 'date'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $fileName = $this->storeStaffImage($request->file('image'));
        $sortOrder = $validated['sort_order'] ?? $this->nextSortOrder();

        $member = Team::create([
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
            'sort_order' => $sortOrder,
            'slug' => $this->uniqueSlug($validated['names']),
            'image' => $fileName,
        ]);

        if (! empty($validated['created_at'])) {
            $member->created_at = Carbon::parse($validated['created_at']);
            $member->save();
        }

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
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'created_at' => ['nullable', 'date'],
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

        if (array_key_exists('sort_order', $validated) && $validated['sort_order'] !== null) {
            $data->sort_order = (int) $validated['sort_order'];
        }

        if (! empty($validated['created_at'])) {
            $data->created_at = Carbon::parse($validated['created_at']);
        }

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

    public function moveUp($id)
    {
        $this->moveMember((int) $id, -1);

        return redirect()->route('staff')->with('success', 'Team order updated.');
    }

    public function moveDown($id)
    {
        $this->moveMember((int) $id, 1);

        return redirect()->route('staff')->with('success', 'Team order updated.');
    }

    public function destroy($id)
    {
        $data = Team::findOrFail($id);
        $this->deleteStaffImage($data->image);
        $data->delete();

        return redirect()->route('staff')->with('success', 'Team member removed successfully.');
    }

    private function moveMember(int $id, int $direction): void
    {
        $ordered = Team::query()->orderedForDisplay()->get();
        $index = $ordered->search(fn (Team $member) => (int) $member->id === $id);

        if ($index === false) {
            return;
        }

        $swapIndex = $index + $direction;
        if ($swapIndex < 0 || $swapIndex >= $ordered->count()) {
            return;
        }

        $current = $ordered[$index];
        $neighbor = $ordered[$swapIndex];

        $currentOrder = (int) $current->sort_order;
        $neighborOrder = (int) $neighbor->sort_order;

        if ($currentOrder === $neighborOrder) {
            $currentOrder = $index + 1;
            $neighborOrder = $swapIndex + 1;
        }

        $current->sort_order = $neighborOrder;
        $neighbor->sort_order = $currentOrder;

        $current->save();
        $neighbor->save();
    }

    private function nextSortOrder(): int
    {
        $max = (int) Team::query()->max('sort_order');

        return $max > 0 ? $max + 1 : 1;
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
