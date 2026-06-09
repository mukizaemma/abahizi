<?php

namespace App\Support;

use App\Models\Background;
use App\Models\Setting;

class PageHeaderService
{
    public static function definitions(): array
    {
        return [
            'factory' => 'Our Factory',
            'products' => 'Products',
            'impact' => 'Impact',
            'impact_employee' => 'Employee Empowerment',
            'impact_community' => 'Community',
            'impact_reports' => 'Impact Reports',
            'contact' => 'Contact',
            'mission' => 'Mission & Vision',
            'what_we_do' => 'What We Do',
            'team' => 'Team',
            'testimonials' => 'Testimonials',
            'updates' => 'News & Updates',
            'about' => 'About Us',
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
    ): array {
        $setting = Setting::firstOrEmpty();
        $about ??= Background::firstOrEmpty();
        $key = $pageKey ?: 'default';
        $stored = static::storedHeaders($setting)[$key] ?? [];

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
            'title' => $title ?? '',
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
}
