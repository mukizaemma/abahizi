<?php

namespace App\Support;

use App\Models\Background;
use App\Models\FactoryGalleryImage;
use App\Models\Slide;
use Illuminate\Support\Facades\Schema;

class SectionBackgroundService
{
    /**
     * @return array<string, array{label: string, help: string, fallbacks: array<int, string>, default: ?string}>
     */
    public static function definitions(): array
    {
        return [
            'core_values_background' => [
                'label' => 'Core values parallax',
                'help' => 'About page and homepage mission/vision band.',
                'fallbacks' => ['image2', 'image1', 'image'],
                'default' => null,
            ],
            'home_process_background' => [
                'label' => 'Home — How we work',
                'help' => 'Parallax background behind the “From brief to delivery” steps on the homepage.',
                'fallbacks' => ['image1', 'image2', 'image'],
                'default' => 'assets/img/breadcrumb/breadcrumb-bg-1.jpg',
            ],
            'impact_cta_background' => [
                'label' => 'Home — Impact section',
                'help' => 'Parallax photo behind homepage impact stats, stories, and CTAs. Recommended 1920×875 or larger; the image is cropped to fill the band on every screen size.',
                'fallbacks' => ['image2', 'image1', 'image'],
                'default' => 'assets/img/slider/slider-bg-3-2.jpg',
            ],
            'home_why_partner_background' => [
                'label' => 'Home — Why brands photo',
                'help' => 'Photo beside “Quality you can scale” on the homepage. Use factory floor, sampling, or finishing work from Masoro.',
                'fallbacks' => ['factory_services_image'],
                'default' => null,
            ],
            'factory_capabilities_background' => [
                'label' => 'Factory capabilities banner',
                'help' => 'Parallax banner with capacity cards — homepage, factory page, impact pages, and other footers.',
                'fallbacks' => ['image2', 'image', 'image1'],
                'default' => 'assets/img/cta/cta-bg-3.jpg',
            ],
            'product_story_background' => [
                'label' => 'Product story section',
                'help' => 'Parallax background on the products page story block.',
                'fallbacks' => ['core_values_background', 'image2', 'image1', 'image'],
                'default' => null,
            ],
            'programs_dual_cta_background' => [
                'label' => 'Mission & vision dual CTA',
                'help' => 'Background for the mission/vision cards band.',
                'fallbacks' => ['core_values_background', 'image2', 'image', 'image1'],
                'default' => null,
            ],
        ];
    }

    public static function editableKeys(): array
    {
        return array_keys(static::definitions());
    }

    public static function resolve(string $field, ?Background $about = null): ?string
    {
        $about ??= Background::firstOrEmpty();
        $definition = static::definitions()[$field] ?? null;

        if ($definition === null) {
            return null;
        }

        $candidates = array_merge([$field], $definition['fallbacks']);

        foreach ($candidates as $candidate) {
            $filename = trim((string) ($about->{$candidate} ?? ''));
            if ($filename !== '') {
                return static::urlFromFilename($filename);
            }
        }

        $default = $definition['default'] ?? null;
        if ($default === null || $default === '') {
            return null;
        }

        return asset($default);
    }

    public static function urlFromFilename(?string $filename): ?string
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return null;
        }

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        return asset('storage/images/' . ltrim($filename, '/'));
    }

    public static function storedFilename(Background $about, string $field): ?string
    {
        $filename = trim((string) ($about->{$field} ?? ''));

        return $filename !== '' ? $filename : null;
    }

    /**
     * Homepage “Why brands” photo: dedicated CMS image, then real factory/slide photos.
     */
    public static function whyFeatureImage(?Background $about = null): ?string
    {
        $about ??= Background::firstOrEmpty();

        $candidates = [];

        $dedicated = static::storedFilename($about, 'home_why_partner_background');
        if ($dedicated) {
            $candidates[] = static::urlFromFilename($dedicated);
        }

        $factoryPage = trim((string) ($about->factory_services_image ?? ''));
        if ($factoryPage !== '') {
            $candidates[] = static::urlFromFilename($factoryPage);
        }

        if (Schema::hasTable('factory_gallery_images')) {
            $gallery = FactoryGalleryImage::query()->orderBy('sort_order')->orderBy('id')->first();
            if ($gallery) {
                $candidates[] = $gallery->url();
            }
        }

        if (Schema::hasTable('slides')) {
            $slide = Slide::query()->whereNotNull('image')->where('image', '!=', '')->latest()->first();
            if ($slide) {
                $candidates[] = Slide::publicImageUrl($slide->image);
            }
        }

        foreach ($candidates as $url) {
            if (! static::isPlaceholderUrl($url)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Homepage partners photo if that block is shown again.
     */
    public static function partnersFeatureImage(?Background $about = null): ?string
    {
        $about ??= Background::firstOrEmpty();

        $candidates = [];

        $dedicated = static::storedFilename($about, 'home_partners_image');
        if ($dedicated) {
            $candidates[] = static::urlFromFilename($dedicated);
        }

        $factoryPage = trim((string) ($about->factory_services_image ?? ''));
        if ($factoryPage !== '') {
            $candidates[] = static::urlFromFilename($factoryPage);
        }

        if (Schema::hasTable('factory_gallery_images')) {
            $gallery = FactoryGalleryImage::query()->orderBy('sort_order')->orderBy('id')->first();
            if ($gallery) {
                $candidates[] = $gallery->url();
            }
        }

        if (Schema::hasTable('slides')) {
            $slide = Slide::query()->whereNotNull('image')->where('image', '!=', '')->latest()->first();
            if ($slide) {
                $candidates[] = Slide::publicImageUrl($slide->image);
            }
        }

        foreach ($candidates as $url) {
            if (! static::isPlaceholderUrl($url)) {
                return $url;
            }
        }

        return null;
    }

    public static function isPlaceholderUrl(?string $url): bool
    {
        $url = strtolower(trim((string) $url));
        if ($url === '') {
            return true;
        }

        return str_contains($url, 'slider-bg-3-')
            || str_contains($url, 'slider-bg-1')
            || str_contains($url, 'breadcrumb-bg');
    }
}
