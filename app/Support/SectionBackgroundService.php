<?php

namespace App\Support;

use App\Models\Background;
use App\Models\FactoryGalleryImage;
use App\Models\Slide;
use Illuminate\Support\Facades\Schema;

class SectionBackgroundService
{
    /**
     * @return array<string, array{label: string, help: string, group: string, fallbacks: array<int, string>, default: ?string}>
     */
    public static function definitions(): array
    {
        return [
            'home_craft_image' => [
                'label' => 'Homepage: “Craft with purpose” photo',
                'help' => 'The photo to the right of that heading (before the product cards). Landscape, about 1200×960 (5:4). This is not the factory page photo.',
                'group' => 'Homepage photos',
                'fallbacks' => ['factory_services_image'],
                'default' => null,
            ],
            'home_why_partner_background' => [
                'label' => 'Homepage: “Quality you can scale” photo',
                'help' => 'The photo to the left of the three numbered cards (01, 02, 03). Portrait crop works well, about 900×1200.',
                'group' => 'Homepage photos',
                'fallbacks' => ['factory_services_image'],
                'default' => null,
            ],
            'impact_cta_background' => [
                'label' => 'Homepage: Impact band photo',
                'help' => 'Full-width photo behind the impact numbers. Use 1920×875 or larger.',
                'group' => 'Homepage photos',
                'fallbacks' => ['image2', 'image1', 'image'],
                'default' => 'assets/img/slider/slider-bg-3-2.jpg',
            ],
            'home_process_background' => [
                'label' => 'Homepage: How we work',
                'help' => 'Background behind the “From brief to delivery” steps, if that band is shown.',
                'group' => 'Homepage photos',
                'fallbacks' => ['image1', 'image2', 'image'],
                'default' => 'assets/img/breadcrumb/breadcrumb-bg-1.jpg',
            ],
            'core_values_background' => [
                'label' => 'About page: core values',
                'help' => 'Parallax band on the About page (and the mission/vision band if used).',
                'group' => 'Other pages',
                'fallbacks' => ['image2', 'image1', 'image'],
                'default' => null,
            ],
            'factory_capabilities_background' => [
                'label' => 'Factory capabilities banner',
                'help' => 'Parallax banner with capacity cards on factory and impact pages.',
                'group' => 'Other pages',
                'fallbacks' => ['image2', 'image', 'image1'],
                'default' => 'assets/img/cta/cta-bg-3.jpg',
            ],
            'product_story_background' => [
                'label' => 'Products page: story photo',
                'help' => 'Background on the products page story block.',
                'group' => 'Other pages',
                'fallbacks' => ['core_values_background', 'image2', 'image1', 'image'],
                'default' => null,
            ],
            'programs_dual_cta_background' => [
                'label' => 'Mission & vision dual CTA',
                'help' => 'Background for the mission/vision cards band.',
                'group' => 'Other pages',
                'fallbacks' => ['core_values_background', 'image2', 'image', 'image1'],
                'default' => null,
            ],
        ];
    }

    /**
     * @return array<string, array<string, array{label: string, help: string, group: string, fallbacks: array<int, string>, default: ?string}>>
     */
    public static function groupedDefinitions(): array
    {
        $groups = [];
        foreach (static::definitions() as $field => $definition) {
            $group = $definition['group'] ?? 'Other pages';
            $groups[$group][$field] = $definition;
        }

        return $groups;
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
     * Homepage “Craft with purpose” photo.
     */
    public static function craftFeatureImage(?Background $about = null): ?string
    {
        $about ??= Background::firstOrEmpty();

        $candidates = [];

        $dedicated = static::storedFilename($about, 'home_craft_image');
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

        foreach ($candidates as $url) {
            if (! static::isPlaceholderUrl($url)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Homepage “Quality you can scale” photo: dedicated CMS image, then real factory/slide photos.
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
