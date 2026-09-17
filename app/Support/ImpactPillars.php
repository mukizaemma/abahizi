<?php

namespace App\Support;

use App\Models\Impact;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ImpactPillars
{
    /**
     * @return array<int, array{title: string, text: string, value: string, icon: string, image: ?string, slug: ?string}>
     */
    public static function items(int $limit = 8): array
    {
        $defaults = static::defaults();
        $icons = array_column($defaults, 'icon');

        $records = collect();
        if (Schema::hasTable('impacts')) {
            $records = Impact::query()
                ->where(function ($q) {
                    $q->whereNull('status')
                        ->orWhere('status', 'Active')
                        ->orWhere('status', 'Publish');
                })
                ->latest()
                ->take($limit)
                ->get();
        }

        $cms = $records
            ->filter(fn ($item) => trim((string) ($item->title ?? '')) !== '')
            ->values();

        if ($cms->isEmpty()) {
            return array_slice($defaults, 0, $limit);
        }

        return $cms->map(function ($item, $index) use ($icons, $defaults) {
            $text = trim(strip_tags(html_entity_decode((string) ($item->description ?? ''))));
            if ($text === '') {
                $text = $defaults[$index]['text'] ?? '';
            }

            $image = trim((string) ($item->image ?? ''));

            return [
                'title' => trim((string) $item->title),
                'text' => Str::limit($text, 140, '…'),
                'value' => trim((string) ($item->value ?? '')),
                'icon' => $icons[$index] ?? 'fa-heart',
                'image' => $image !== '' ? asset('storage/images/impacts/' . ltrim($image, '/')) : null,
                'slug' => $item->slug ?? null,
            ];
        })->all();
    }

    /**
     * @return array<int, array{title: string, text: string, value: string, icon: string, image: null, slug: null}>
     */
    public static function defaults(): array
    {
        return [
            [
                'title' => __('site.landing.pillar_1_title'),
                'text' => __('site.landing.pillar_1_text'),
                'value' => '',
                'icon' => 'fa-briefcase-medical',
                'image' => null,
                'slug' => null,
            ],
            [
                'title' => __('site.landing.pillar_2_title'),
                'text' => __('site.landing.pillar_2_text'),
                'value' => '',
                'icon' => 'fa-graduation-cap',
                'image' => null,
                'slug' => null,
            ],
            [
                'title' => __('site.landing.pillar_3_title'),
                'text' => __('site.landing.pillar_3_text'),
                'value' => '',
                'icon' => 'fa-heart-pulse',
                'image' => null,
                'slug' => null,
            ],
            [
                'title' => __('site.landing.pillar_4_title'),
                'text' => __('site.landing.pillar_4_text'),
                'value' => '',
                'icon' => 'fa-people-roof',
                'image' => null,
                'slug' => null,
            ],
        ];
    }
}
