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
                'label' => 'Homepage story photo',
                'help' => 'Photo beside the homepage “Our Story” text. Landscape, about 1200×960. Not the factory page photo.',
                'group' => 'Homepage',
                'fallbacks' => ['factory_services_image'],
                'default' => null,
            ],
            'impact_cta_background' => [
                'label' => 'Homepage impact photos',
                'help' => 'Used among the four impact-band photos. 1920×875 or larger works well.',
                'group' => 'Homepage',
                'fallbacks' => ['image2', 'image1', 'image'],
                'default' => 'assets/img/slider/slider-bg-3-2.jpg',
            ],
            'core_values_background' => [
                'label' => 'Mission page: core values band',
                'help' => 'Parallax photo behind core values on Mission & Vision.',
                'group' => 'About pages',
                'fallbacks' => ['image2', 'image1', 'image'],
                'default' => null,
            ],
            'programs_dual_cta_background' => [
                'label' => 'Our Story: mission & vision band',
                'help' => 'Background behind the mission and vision cards on Our Story.',
                'group' => 'About pages',
                'fallbacks' => ['core_values_background', 'image2', 'image', 'image1'],
                'default' => null,
            ],
            'factory_capabilities_background' => [
                'label' => 'Factory capabilities banner',
                'help' => 'Parallax banner with capacity cards on factory and impact pages.',
                'group' => 'Factory & products',
                'fallbacks' => ['image2', 'image', 'image1'],
                'default' => 'assets/img/cta/cta-bg-3.jpg',
            ],
            'product_story_background' => [
                'label' => 'Products page: story photo',
                'help' => 'Background on the products page story block (when the catalog is public).',
                'group' => 'Factory & products',
                'fallbacks' => ['core_values_background', 'image2', 'image1', 'image'],
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
