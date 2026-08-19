<?php

namespace App\Http\Controllers;

use App\Support\MediaLibraryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    public function __construct(protected MediaLibraryService $media)
    {
    }

    public function index(Request $request): View
    {
        $files = $this->media->paginateFiles($request, 12);

        return view('admin.media-library.index', [
            'files' => $files,
            'duplicatesOnly' => $request->boolean('duplicates'),
        ]);
    }

    public function library(Request $request): JsonResponse
    {
        $files = $this->media->paginateFiles($request, 12);

        return response()->json([
            'data' => $files->getCollection()->map(fn (array $file) => [
                'path' => $file['path'],
                'url' => $file['url'],
                'name' => $file['name'],
                'size_label' => $file['size_label'],
                'usage_count' => $file['usage_count'],
            ])->values(),
            'current_page' => $files->currentPage(),
            'last_page' => $files->lastPage(),
            'total' => $files->total(),
            'next_page_url' => $files->nextPageUrl(),
            'prev_page_url' => $files->previousPageUrl(),
        ]);
    }

    public function usages(Request $request): JsonResponse
    {
        $path = (string) $request->query('path', '');
        $file = $this->media->find($path);
        if (! $file) {
            return response()->json(['message' => 'Image not found.'], 404);
        }

        return response()->json($file);
    }

    public function replace(Request $request): RedirectResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
            'usage_keys' => ['nullable', 'array'],
            'usage_keys.*' => ['string'],
            'existing_path' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $oldPath = $request->input('path');
        $usageKeys = $request->input('usage_keys', []);
        if ($usageKeys === []) {
            return back()->with('warning', 'Select at least one place to update, or skip and keep those uses.');
        }

        $newPath = $request->input('existing_path');
        if ($request->hasFile('image')) {
            $directory = trim(dirname($oldPath), '.');
            $newPath = $this->media->storeReplacement($request->file('image'), $directory === '' ? 'images/media' : $directory);
        }

        if (! $newPath) {
            return back()->with('error', 'Choose an existing image or upload a replacement first.');
        }

        $updated = $this->media->replaceUsages($oldPath, $newPath, $usageKeys);

        return back()->with('success', $updated . ' use' . ($updated === 1 ? '' : 's') . ' now point to the new image.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = $request->input('path');
        $usages = $this->media->usagesFor($path);
        if ($usages !== []) {
            return back()->with('error', 'This image is still used in ' . count($usages) . ' place(s). Change or skip those uses first, then remove it.');
        }

        if (! Storage::disk('public')->exists($path)) {
            return back()->with('warning', 'The file was already gone.');
        }

        $this->media->deleteFile($path);

        return back()->with('success', 'Image removed from the system.');
    }
}
