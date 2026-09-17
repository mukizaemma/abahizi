<?php

namespace App\Support;

use App\Models\Setting;

class SiteCopy
{
    /**
     * Editable homepage (and related) copy. Empty CMS values fall back to lang/site.php.
     *
     * @return array<string, array{label: string, type: string, rows?: int, help?: string, fields: array<string, array{label: string, type: string, rows?: int}>}>
     */
    public static function groups(): array
    {
        return [
            'hero' => [
                'label' => 'Hero (homepage banner)',
                'help' => 'Headline and subtitle are also under Homepage hero in this same settings screen if those fields exist. These buttons and highlight bar sit on the banner.',
                'fields' => [
                    'cta_story' => ['label' => 'Primary button', 'type' => 'text'],
                    'cta_impact' => ['label' => 'Secondary button', 'type' => 'text'],
                    'hero_seal' => ['label' => 'Circular seal text', 'type' => 'text'],
                    'bar_1' => ['label' => 'Highlight 1', 'type' => 'text'],
                    'bar_2' => ['label' => 'Highlight 2', 'type' => 'text'],
                    'bar_3' => ['label' => 'Highlight 3', 'type' => 'text'],
                    'bar_4' => ['label' => 'Highlight 4', 'type' => 'text'],
                ],
            ],
            'story' => [
                'label' => 'Our Story',
                'fields' => [
                    'about_eyebrow' => ['label' => 'Small heading (eyebrow)', 'type' => 'text'],
                    'about_title' => ['label' => 'Main title', 'type' => 'textarea', 'rows' => 3],
                    'about_cta' => ['label' => 'Button label', 'type' => 'text'],
                ],
            ],
            'craft' => [
                'label' => 'Our Craft',
                'fields' => [
                    'products_title' => ['label' => 'Small heading (eyebrow)', 'type' => 'text'],
                    'products_lead' => ['label' => 'Main title', 'type' => 'textarea', 'rows' => 3],
                    'products_view_more' => ['label' => 'View more bags button', 'type' => 'text'],
                    'craft_1_title' => ['label' => 'Point 1 title', 'type' => 'text'],
                    'craft_1_text' => ['label' => 'Point 1 text', 'type' => 'textarea', 'rows' => 2],
                    'craft_2_title' => ['label' => 'Point 2 title', 'type' => 'text'],
                    'craft_2_text' => ['label' => 'Point 2 text', 'type' => 'textarea', 'rows' => 2],
                    'craft_3_title' => ['label' => 'Point 3 title', 'type' => 'text'],
                    'craft_3_text' => ['label' => 'Point 3 text', 'type' => 'textarea', 'rows' => 2],
                    'craft_4_title' => ['label' => 'Point 4 title', 'type' => 'text'],
                    'craft_4_text' => ['label' => 'Point 4 text', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            'impact' => [
                'label' => 'Impact band',
                'help' => 'The accent phrase is coloured inside the main title when it appears in that title.',
                'fields' => [
                    'impact_title' => ['label' => 'Main title', 'type' => 'textarea', 'rows' => 2],
                    'impact_accent' => ['label' => 'Coloured phrase', 'type' => 'text'],
                    'impact_lead' => ['label' => 'Supporting text', 'type' => 'textarea', 'rows' => 3],
                    'impact_cta_explore' => ['label' => 'Primary button', 'type' => 'text'],
                    'impact_cta_community' => ['label' => 'Secondary button', 'type' => 'text'],
                ],
            ],
            'values' => [
                'label' => 'Our Values',
                'fields' => [
                    'move_eyebrow' => ['label' => 'Small heading (eyebrow)', 'type' => 'text'],
                    'move_title' => ['label' => 'Main title', 'type' => 'textarea', 'rows' => 3],
                    'move_text' => ['label' => 'Supporting text', 'type' => 'textarea', 'rows' => 4],
                    'move_cta' => ['label' => 'Button label', 'type' => 'text'],
                    'value_1_title' => ['label' => 'Value 1 title', 'type' => 'text'],
                    'value_1_text' => ['label' => 'Value 1 text', 'type' => 'textarea', 'rows' => 2],
                    'value_2_title' => ['label' => 'Value 2 title', 'type' => 'text'],
                    'value_2_text' => ['label' => 'Value 2 text', 'type' => 'textarea', 'rows' => 2],
                    'value_3_title' => ['label' => 'Value 3 title', 'type' => 'text'],
                    'value_3_text' => ['label' => 'Value 3 text', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            'contact' => [
                'label' => 'Get in touch (homepage)',
                'fields' => [
                    'contact_title' => ['label' => 'Title', 'type' => 'text'],
                    'contact_lead' => ['label' => 'Supporting text', 'type' => 'textarea', 'rows' => 2],
                    'social_title' => ['label' => 'Social / Instagram title', 'type' => 'text'],
                    'social_cta' => ['label' => 'Social button', 'type' => 'text'],
                ],
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function keys(): array
    {
        $keys = [];
        foreach (static::groups() as $group) {
            foreach (array_keys($group['fields']) as $key) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    public static function get(string $key, ?Setting $setting = null): string
    {
        $stored = trim((string) (static::stored($setting)[$key] ?? ''));
        if ($stored !== '') {
            return $stored;
        }

        return (string) __('site.landing.'.$key);
    }

    public static function impactTitleHtml(?Setting $setting = null): string
    {
        $title = static::get('impact_title', $setting);
        $accent = static::get('impact_accent', $setting);
        $safe = e($title);
        $safeAccent = e($accent);

        if ($safeAccent !== '' && $safeAccent !== $safe && str_contains($safe, $safeAccent)) {
            return str_replace($safeAccent, '<span class="lh-accent">'.$safeAccent.'</span>', $safe);
        }

        return $safe;
    }

    /**
     * @return array<string, string>
     */
    public static function stored(?Setting $setting = null): array
    {
        $setting ??= Setting::firstOrEmpty();
        if (! is_array($setting->landing_copy ?? null)) {
            return [];
        }

        return $setting->landing_copy;
    }
}
