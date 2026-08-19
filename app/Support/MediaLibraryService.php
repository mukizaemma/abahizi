<?php

namespace App\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MediaLibraryService
{
    public const MAX_BYTES = 716800; // 700 KB

    protected ?Collection $fileCache = null;

    public function paginateFiles(Request $request, int $perPage = 12): LengthAwarePaginator
    {
        $files = $this->files();
        if ($request->boolean('duplicates')) {
            $files = $files->filter(fn (array $file) => ($file['duplicate_count'] ?? 1) > 1)->values();
        }

        $page = max(1, (int) $request->integer('page', 1));
        $total = $files->count();
        $slice = $files->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    public function files(): Collection
    {
        if ($this->fileCache instanceof Collection) {
            return $this->fileCache;
        }

        $disk = Storage::disk('public');
        $allUsages = $this->allUsages();
        $hashCounts = [];

        $items = collect($disk->allFiles())
            ->filter(fn (string $path) => $this->isImagePath($path))
            ->sortByDesc(fn (string $path) => $disk->lastModified($path))
            ->values()
            ->map(function (string $path) use ($disk, $allUsages, &$hashCounts) {
                $absolute = $disk->path($path);
                $hash = is_file($absolute) ? (string) md5_file($absolute) : $path;
                $hashCounts[$hash] = ($hashCounts[$hash] ?? 0) + 1;
                $size = is_file($absolute) ? (int) filesize($absolute) : 0;
                $usages = $allUsages->get($path, collect())->values()->all();

                return [
                    'path' => $path,
                    'url' => asset('storage/' . ltrim($path, '/')),
                    'name' => basename($path),
                    'size' => $size,
                    'size_label' => $this->formatBytes($size),
                    'hash' => $hash,
                    'modified' => is_file($absolute) ? date('d M Y', filemtime($absolute)) : '',
                    'usages' => $usages,
                    'usage_count' => count($usages),
                ];
            });

        $this->fileCache = $items->map(function (array $file) use ($hashCounts) {
            $file['duplicate_count'] = $hashCounts[$file['hash']] ?? 1;

            return $file;
        });

        return $this->fileCache;
    }

    public function find(string $path): ?array
    {
        $normalized = $this->normalizePath($path);

        return $this->files()->first(fn (array $file) => $file['path'] === $normalized);
    }

    public function usagesFor(string $path): array
    {
        $file = $this->find($path);

        return $file['usages'] ?? [];
    }

    public function replaceUsages(string $oldPath, string $newPath, array $usageKeys): int
    {
        $this->fileCache = null;
        $oldPath = $this->normalizePath($oldPath);
        $newPath = $this->normalizePath($newPath);
        $updated = 0;

        foreach ($this->usagesFor($oldPath) as $usage) {
            if (! in_array($usage['key'], $usageKeys, true)) {
                continue;
            }

            if (($usage['table'] ?? '') === 'settings_json') {
                $updated += $this->replaceSettingsPath($usage, $newPath) ? 1 : 0;
                continue;
            }

            $stored = (string) ($usage['stored'] ?? '');
            $writeValue = $this->valueForStorage($stored, $newPath);
            DB::table($usage['table'])
                ->where('id', $usage['id'])
                ->update([$usage['column'] => $writeValue]);
            $updated++;
        }

        $this->fileCache = null;

        return $updated;
    }

    public function deleteFile(string $path): bool
    {
        $this->fileCache = null;
        $path = $this->normalizePath($path);
        if ($this->usagesFor($path) !== []) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    public function storeReplacement($file, string $directory): string
    {
        $this->fileCache = null;
        $directory = trim($directory, '/');
        if ($directory === '' || $directory === '.') {
            $directory = 'images/media';
        }

        return $file->store($directory, 'public');
    }

    public function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1048576) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return number_format($bytes / 1048576, 2) . ' MB';
    }

    protected function allUsages(): Collection
    {
        $grouped = collect();

        foreach ($this->sources() as $source) {
            if (! Schema::hasTable($source['table']) || ! Schema::hasColumn($source['table'], $source['column'])) {
                continue;
            }

            $rows = DB::table($source['table'])->select(['id', $source['column']])->get();
            foreach ($rows as $row) {
                $stored = (string) ($row->{$source['column']} ?? '');
                if ($stored === '') {
                    continue;
                }
                $matched = $this->diskPathForStored($stored);
                if (! $matched) {
                    continue;
                }
                $usage = [
                    'key' => $source['table'] . '|' . $source['column'] . '|' . $row->id,
                    'table' => $source['table'],
                    'column' => $source['column'],
                    'id' => $row->id,
                    'label' => $source['label'],
                    'edit_url' => $this->editUrl($source, (int) $row->id),
                    'stored' => $stored,
                ];
                $grouped->push([$matched, $usage]);
            }
        }

        foreach ($this->settingsHeaderUsages() as $pair) {
            $grouped->push($pair);
        }

        return $grouped->groupBy(fn (array $pair) => $pair[0])->map(fn (Collection $pairs) => $pairs->map(fn (array $pair) => $pair[1]));
    }

    protected function settingsHeaderUsages(): array
    {
        if (! Schema::hasTable('settings') || ! Schema::hasColumn('settings', 'page_headers')) {
            return [];
        }

        $row = DB::table('settings')->select(['id', 'page_headers'])->first();
        if (! $row || empty($row->page_headers)) {
            return [];
        }

        $headers = is_array($row->page_headers) ? $row->page_headers : json_decode($row->page_headers, true);
        if (! is_array($headers)) {
            return [];
        }

        $pairs = [];
        foreach ($headers as $pageKey => $header) {
            $stored = (string) ($header['image'] ?? '');
            if ($stored === '') {
                continue;
            }
            $matched = $this->diskPathForStored($stored);
            if (! $matched) {
                continue;
            }
            $pairs[] = [$matched, [
                'key' => 'settings_json|page_headers.' . $pageKey . '|' . $row->id,
                'table' => 'settings_json',
                'column' => 'page_headers.' . $pageKey,
                'id' => $row->id,
                'label' => 'Page header (' . $pageKey . ')',
                'edit_url' => route('settings'),
                'stored' => $stored,
                'page_key' => $pageKey,
            ]];
        }

        return $pairs;
    }

    protected function diskPathForStored(string $stored): ?string
    {
        $stored = $this->normalizePath($stored);
        $disk = Storage::disk('public');
        if ($stored !== '' && $disk->exists($stored)) {
            return $stored;
        }

        $prefixed = [
            'images/' . $stored,
            'images/staff/' . $stored,
            'images/slides/' . $stored,
            'images/news/' . $stored,
            'images/gallery/' . $stored,
            'images/page-headers/' . ltrim($stored, '/'),
        ];
        foreach ($prefixed as $candidate) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $stored !== '' ? $stored : null;
    }

    protected function sources(): array
    {
        return [
            ['table' => 'news', 'column' => 'image', 'label' => 'Updates cover', 'edit_route' => 'editBlog'],
            ['table' => 'blogimages', 'column' => 'gallery', 'label' => 'Updates gallery', 'edit_route' => null],
            ['table' => 'slides', 'column' => 'image', 'label' => 'Home slide', 'edit_route' => 'editSlide'],
            ['table' => 'images', 'column' => 'image', 'label' => 'Craft gallery', 'edit_route' => 'editGallery'],
            ['table' => 'teams', 'column' => 'image', 'label' => 'Team member', 'edit_route' => 'editStaff'],
            ['table' => 'testimonies', 'column' => 'image', 'label' => 'Testimonial', 'edit_route' => 'editTestimony'],
            ['table' => 'partners', 'column' => 'image', 'label' => 'Partner', 'edit_route' => 'editPartner'],
            ['table' => 'programs', 'column' => 'image', 'label' => 'Program cover', 'edit_route' => 'editProgram'],
            ['table' => 'programimages', 'column' => 'image', 'label' => 'Program gallery', 'edit_route' => null],
            ['table' => 'activities', 'column' => 'image', 'label' => 'Community initiative', 'edit_route' => 'editProject'],
            ['table' => 'projectimages', 'column' => 'image', 'label' => 'Community gallery', 'edit_route' => null],
            ['table' => 'products', 'column' => 'image', 'label' => 'Product cover', 'edit_route' => 'catalogProducts.edit'],
            ['table' => 'product_images', 'column' => 'image', 'label' => 'Product gallery', 'edit_route' => null],
            ['table' => 'impacts', 'column' => 'image', 'label' => 'Impact stat', 'edit_route' => 'editImpact'],
            ['table' => 'annual_report_images', 'column' => 'image', 'label' => 'Impact report gallery', 'edit_route' => null],
            ['table' => 'factory_gallery_images', 'column' => 'image', 'label' => 'Factory gallery', 'edit_route' => null],
            ['table' => 'events', 'column' => 'image', 'label' => 'Event', 'edit_route' => 'editEvent'],
            ['table' => 'campains', 'column' => 'image', 'label' => 'Campaign', 'edit_route' => 'editCampain'],
            ['table' => 'backgrounds', 'column' => 'image', 'label' => 'About cover', 'edit_route' => 'about'],
            ['table' => 'backgrounds', 'column' => 'image1', 'label' => 'Home background', 'edit_route' => 'about'],
            ['table' => 'backgrounds', 'column' => 'image2', 'label' => 'Pages header image', 'edit_route' => 'about'],
            ['table' => 'backgrounds', 'column' => 'impact_cta_background', 'label' => 'Home impact background', 'edit_route' => 'about'],
            ['table' => 'backgrounds', 'column' => 'home_partners_image', 'label' => 'Home partners photo', 'edit_route' => 'about'],
            ['table' => 'backgrounds', 'column' => 'model_image', 'label' => 'Our Model image', 'edit_route' => 'about'],
            ['table' => 'backgrounds', 'column' => 'factory_services_image', 'label' => 'Factory page photo', 'edit_route' => 'factory.admin.overview'],
            ['table' => 'backgrounds', 'column' => 'factory_community_impact_image', 'label' => 'Factory impact image', 'edit_route' => 'factory.admin.impact'],
            ['table' => 'backgrounds', 'column' => 'factory_training_facilities_image', 'label' => 'Factory training image', 'edit_route' => 'factory.admin.training'],
            ['table' => 'abouts', 'column' => 'backImage', 'label' => 'About back image', 'edit_route' => 'about'],
            ['table' => 'settings', 'column' => 'logo', 'label' => 'Site logo', 'edit_route' => 'settings'],
            ['table' => 'settings', 'column' => 'page_header_image', 'label' => 'Default page header', 'edit_route' => 'settings'],
            ['table' => 'settings', 'column' => 'hero_poster', 'label' => 'Hero poster', 'edit_route' => 'settings'],
        ];
    }

    protected function replaceSettingsPath(array $usage, string $newPath): bool
    {
        $row = DB::table('settings')->where('id', $usage['id'])->first();
        if (! $row) {
            return false;
        }

        $headers = is_array($row->page_headers ?? null)
            ? $row->page_headers
            : json_decode($row->page_headers ?? '[]', true);
        if (! is_array($headers)) {
            return false;
        }

        $pageKey = $usage['page_key'] ?? null;
        if (! $pageKey || empty($headers[$pageKey]['image'])) {
            return false;
        }

        $headers[$pageKey]['image'] = $this->valueForStorage((string) $headers[$pageKey]['image'], $newPath);
        DB::table('settings')->where('id', $row->id)->update([
            'page_headers' => json_encode($headers),
        ]);

        return true;
    }

    protected function editUrl(array $source, int $id): ?string
    {
        $route = $source['edit_route'] ?? null;
        if (! $route) {
            return null;
        }

        try {
            if (in_array($route, ['about', 'settings', 'factory.admin.overview', 'factory.admin.services', 'factory.admin.impact', 'factory.admin.training'], true)) {
                return route($route);
            }

            return route($route, $id);
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function valueForStorage(string $previous, string $newPath): string
    {
        $previous = ltrim($previous, '/');
        $newPath = $this->normalizePath($newPath);

        if ($previous !== '' && ! str_contains($previous, '/')) {
            return basename($newPath);
        }

        return $newPath;
    }

    protected function normalizePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#^storage/#', '', ltrim($path, '/')) ?? $path;
        $path = preg_replace('#^public/#', '', $path) ?? $path;

        return ltrim($path, '/');
    }

    protected function isImagePath(string $path): bool
    {
        return (bool) preg_match('/\.(jpe?g|png|gif|webp|svg)$/i', $path);
    }
}
