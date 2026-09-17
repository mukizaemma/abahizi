<?php

namespace App\Support;

use App\Models\Background;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PageHeaderService
{
    public static function definitions(): array
    {
        return [
            'about' => 'Our Story',
            'mission' => 'Mission & Vision',
            'what_we_do' => 'What We Do',
            'team' => 'Team',
            'testimonials' => 'Testimonials',
            'factory' => 'Our Factory',
            'products' => 'Products',
            'impact' => 'Impact',
            'impact_employee' => 'Employee Empowerment',
            'impact_community' => 'Community',
            'impact_reports' => 'Impact Reports',
            'updates' => 'Updates',
            'gallery' => 'Gallery',
            'contact' => 'Get In Touch',
            'default' => 'Other pages (fallback)',
        ];
    }

    public static function editablePageKeys(): array
    {
        return array_keys(static::definitions());
    }

    public static function storedHeaders(?Setting $setting = null): array
    {
        $setting ??= Setting::firstOrEmpty();

        if (! is_array($setting->page_headers ?? null)) {
            return [];
        }

        return $setting->page_headers;
    }

    public static function resolve(
        ?string $pageKey,
        ?string $title = null,
        ?string $caption = null,
        ?string $image = null,
        ?Background $about = null,
        bool $titleLocked = false,
    ): array {
        $setting = Setting::firstOrEmpty();
        $about ??= Background::firstOrEmpty();
        $key = $pageKey ?: 'default';
        $stored = static::storedHeaders($setting)[$key] ?? [];

        $resolvedTitle = trim((string) ($title ?? ''));
        if (! $titleLocked) {
            $storedTitle = trim((string) ($stored['title'] ?? ''));
            if ($storedTitle !== '') {
                $resolvedTitle = $storedTitle;
            }
        }

        $resolvedCaption = trim((string) ($stored['caption'] ?? ''));
        if ($resolvedCaption === '') {
            $resolvedCaption = trim((string) ($caption ?? ''));
        }
        if ($resolvedCaption === '') {
            $resolvedCaption = trim((string) ($setting->page_header_caption ?? ''));
        }

        $resolvedImage = static::imageUrlFromStored($stored['image'] ?? null)
            ?? $image
            ?? static::imageUrlFromStored($setting->page_header_image ?? null)
            ?? static::aboutFallbackImage($about);

        return [
            'title' => $resolvedTitle,
            'caption' => $resolvedCaption !== '' ? $resolvedCaption : null,
            'image' => $resolvedImage,
        ];
    }

    public static function imageUrlFromStored(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        return asset('storage/images/' . $normalized);
    }

    protected static function aboutFallbackImage(Background $about): ?string
    {
        foreach (['image2', 'image1', 'image', 'factory_services_image'] as $field) {
            if (! empty($about->{$field})) {
                return asset('storage/images/' . ltrim($about->{$field}, '/'));
            }
        }

        return asset('assets/img/slider/slider-3-1.jpg');
    }

    public static function saveFromRequest(Request $request): void
    {
        if (! Schema::hasTable('settings') || ! Schema::hasColumn('settings', 'page_headers')) {
            return;
        }

        $key = trim((string) $request->input('page_header_key', ''));
        if ($key === '' || ! in_array($key, static::editablePageKeys(), true)) {
            return;
        }

        $setting = Setting::query()->first();
        if (! $setting) {
            return;
        }

        $headers = is_array($setting->page_headers) ? $setting->page_headers : [];
        $existing = (array) ($headers[$key] ?? []);

        if ($request->exists('header_title')) {
            $existing['title'] = trim((string) $request->input('header_title'));
        }
        if ($request->exists('header_caption')) {
            $existing['caption'] = trim((string) $request->input('header_caption'));
        }
        if ($request->hasFile('header_image')) {
            $path = $request->file('header_image')->store('public/images/page-headers');
            $existing['image'] = 'page-headers/' . basename($path);
        }

        $headers[$key] = $existing;
        $setting->page_headers = $headers;
        $setting->save();
    }
}
