<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SiteCopy
{
    /**
     * Editable public copy, grouped by the admin screen that owns it.
     * Empty CMS values fall back to lang/site.php.
     *
     * @return array<string, array{label: string, help?: string, fields: array<string, array{label: string, type: string, rows?: int, lang?: string}>}>
     */
    public static function groups(): array
    {
        return [
            'hero' => [
                'label' => 'Banner buttons and highlights',
                'help' => 'These sit on the homepage banner, together with the headline and subtitle.',
                'fields' => [
                    'cta_story' => ['label' => 'Primary button', 'type' => 'text'],
                    'cta_impact' => ['label' => 'Secondary button', 'type' => 'text'],
                    'hero_seal' => ['label' => 'Circular seal text', 'type' => 'text'],
                ],
            ],
            'story' => [
                'label' => 'Our Story',
                'fields' => [
                    'about_eyebrow' => ['label' => 'Homepage small heading', 'type' => 'text'],
                    'about_title' => ['label' => 'Homepage main title', 'type' => 'textarea', 'rows' => 3],
                    'about_cta' => ['label' => 'Homepage button', 'type' => 'text'],
                    'about_bg_heading' => ['label' => 'Our Story page section heading', 'type' => 'text', 'lang' => 'site.cms.about_bg_heading'],
                    'about_team_heading' => ['label' => 'Team block heading on Our Story', 'type' => 'text', 'lang' => 'site.cms.about_team_heading'],
                    'about_team_lead' => ['label' => 'Team block text on Our Story', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.cms.about_team_lead'],
                ],
            ],
            'mission' => [
                'label' => 'Mission & vision cards',
                'fields' => [
                    'mission_card_title' => ['label' => 'Mission card title', 'type' => 'text', 'lang' => 'site.cms.mission_card_title'],
                    'vision_card_title' => ['label' => 'Vision card title', 'type' => 'text', 'lang' => 'site.cms.vision_card_title'],
                ],
            ],
            'values' => [
                'label' => 'Our Values',
                'fields' => [
                    'move_eyebrow' => ['label' => 'Homepage small heading', 'type' => 'text'],
                    'move_title' => ['label' => 'Homepage main title', 'type' => 'textarea', 'rows' => 3],
                    'move_text' => ['label' => 'Homepage supporting text', 'type' => 'textarea', 'rows' => 4],
                    'move_cta' => ['label' => 'Homepage button', 'type' => 'text'],
                    'value_1_title' => ['label' => 'Value 1 title', 'type' => 'text'],
                    'value_1_text' => ['label' => 'Value 1 text', 'type' => 'textarea', 'rows' => 2],
                    'value_2_title' => ['label' => 'Value 2 title', 'type' => 'text'],
                    'value_2_text' => ['label' => 'Value 2 text', 'type' => 'textarea', 'rows' => 2],
                    'value_3_title' => ['label' => 'Value 3 title', 'type' => 'text'],
                    'value_3_text' => ['label' => 'Value 3 text', 'type' => 'textarea', 'rows' => 2],
                    'values_page_heading' => ['label' => '“Our Core Values” heading on inner pages', 'type' => 'text', 'lang' => 'site.cms.values_page_heading'],
                ],
            ],
            'what_we_do' => [
                'label' => 'What We Do',
                'fields' => [
                    'what_we_do_how_title' => ['label' => 'How it works heading', 'type' => 'text', 'lang' => 'site.cms.what_we_do_how_title'],
                    'what_we_do_how_lead' => ['label' => 'How it works supporting text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.cms.what_we_do_how_lead'],
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
                    'product_1_info' => ['label' => 'Card 1 description', 'type' => 'textarea', 'rows' => 2],
                    'product_2_info' => ['label' => 'Card 2 description', 'type' => 'textarea', 'rows' => 2],
                    'product_3_info' => ['label' => 'Card 3 description', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            'products_page' => [
                'label' => 'Products page sections',
                'help' => 'Headings on /products (not the homepage craft block).',
                'fields' => [
                    'products_paths_eyebrow' => ['label' => 'Work with us — small heading', 'type' => 'text', 'lang' => 'site.products_page.paths_eyebrow'],
                    'products_paths_title' => ['label' => 'Work with us — title', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.products_page.paths_title'],
                    'products_paths_lead' => ['label' => 'Work with us — text', 'type' => 'textarea', 'rows' => 3, 'lang' => 'site.products_page.paths_lead'],
                    'products_buy_title' => ['label' => 'Source bags — title', 'type' => 'text', 'lang' => 'site.products_page.buy_title'],
                    'products_buy_text' => ['label' => 'Source bags — text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.products_page.buy_text'],
                    'products_buy_cta' => ['label' => 'Source bags — button', 'type' => 'text', 'lang' => 'site.products_page.buy_cta'],
                    'products_make_title' => ['label' => 'Produce with us — title', 'type' => 'text', 'lang' => 'site.products_page.make_title'],
                    'products_make_text' => ['label' => 'Produce with us — text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.products_page.make_text'],
                    'products_make_cta' => ['label' => 'Produce with us — button', 'type' => 'text', 'lang' => 'site.products_page.make_cta'],
                    'products_gallery_title' => ['label' => 'Gallery heading', 'type' => 'text', 'lang' => 'site.products_page.gallery_title'],
                    'products_gallery_lead' => ['label' => 'Gallery text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.products_page.gallery_lead'],
                    'products_catalog_title' => ['label' => 'Catalog heading', 'type' => 'text', 'lang' => 'site.products_page.catalog_title'],
                    'products_catalog_lead' => ['label' => 'Catalog text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.products_page.catalog_lead'],
                    'products_craft_cta' => ['label' => 'Craft block button on this page', 'type' => 'text', 'lang' => 'site.products_page.craft_cta'],
                ],
            ],
            'impact' => [
                'label' => 'Homepage impact band',
                'help' => 'The accent phrase is coloured inside the main title when it appears in that title.',
                'fields' => [
                    'impact_title' => ['label' => 'Main title', 'type' => 'textarea', 'rows' => 2],
                    'impact_accent' => ['label' => 'Coloured phrase', 'type' => 'text'],
                    'impact_lead' => ['label' => 'Supporting text', 'type' => 'textarea', 'rows' => 3],
                    'impact_cta_explore' => ['label' => 'Primary button', 'type' => 'text'],
                    'impact_cta_community' => ['label' => 'Secondary button', 'type' => 'text'],
                ],
            ],
            'impact_hub' => [
                'label' => 'Impact hub cards',
                'help' => 'The three cards on the Impact landing page.',
                'fields' => [
                    'impact_hub_card_cta' => ['label' => 'Card button', 'type' => 'text', 'lang' => 'site.impact.hub_card_cta'],
                    'impact_empower_lead' => ['label' => 'Employee empowerment card text', 'type' => 'textarea', 'rows' => 3, 'lang' => 'site.impact.empower_lead'],
                    'impact_community_lead' => ['label' => 'Community card text', 'type' => 'textarea', 'rows' => 3, 'lang' => 'site.impact.community_lead'],
                    'impact_reports_lead' => ['label' => 'Reports card text', 'type' => 'textarea', 'rows' => 3, 'lang' => 'site.impact.reports_lead'],
                    'impact_empower_metrics_title' => ['label' => 'Employee empowerment metrics heading', 'type' => 'text', 'lang' => 'site.impact.empower_metrics_title'],
                ],
            ],
            'contact' => [
                'label' => 'Get in touch',
                'fields' => [
                    'contact_title' => ['label' => 'Homepage title', 'type' => 'text'],
                    'contact_lead' => ['label' => 'Homepage supporting text', 'type' => 'textarea', 'rows' => 2],
                    'visit_title' => ['label' => 'Visit our facility title', 'type' => 'text'],
                    'social_title' => ['label' => 'Social / Instagram title', 'type' => 'text'],
                    'social_cta' => ['label' => 'Social button', 'type' => 'text'],
                    'contact_intro_title' => ['label' => 'Contact page heading', 'type' => 'text', 'lang' => 'site.cms.contact_intro_title'],
                    'contact_intro_lead' => ['label' => 'Contact page text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.cms.contact_intro_lead'],
                ],
            ],
            'team' => [
                'label' => 'Team page',
                'fields' => [
                    'team_page_heading' => ['label' => 'Heading', 'type' => 'text', 'lang' => 'site.nav.team'],
                    'team_page_lead' => ['label' => 'Supporting text', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.cms.team_page_lead'],
                ],
            ],
            'updates' => [
                'label' => 'Updates page',
                'fields' => [
                    'updates_heading' => ['label' => 'Heading', 'type' => 'text', 'lang' => 'site.updates.heading'],
                ],
            ],
            'gallery' => [
                'label' => 'Gallery page',
                'fields' => [
                    'gallery_intro' => ['label' => 'Header caption', 'type' => 'textarea', 'rows' => 2, 'lang' => 'site.cms.gallery_intro'],
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

    public static function langKey(string $key): string
    {
        foreach (static::groups() as $group) {
            if (isset($group['fields'][$key]['lang'])) {
                return $group['fields'][$key]['lang'];
            }
        }

        return 'site.landing.'.$key;
    }

    public static function placeholder(string $key): string
    {
        return (string) __(static::langKey($key));
    }

    public static function get(string $key, ?Setting $setting = null): string
    {
        $stored = trim((string) (static::stored($setting)[$key] ?? ''));
        if ($stored !== '') {
            return $stored;
        }

        return (string) __(static::langKey($key));
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

    public static function saveFromRequest(Request $request): void
    {
        if (! Schema::hasTable('settings') || ! Schema::hasColumn('settings', 'landing_copy')) {
            return;
        }

        $setting = Setting::query()->first();
        if (! $setting) {
            return;
        }

        $incoming = (array) $request->input('landing_copy', []);
        if ($incoming === []) {
            return;
        }

        $copy = is_array($setting->landing_copy) ? $setting->landing_copy : [];
        $allowed = static::keys();
        $changed = false;

        foreach ($incoming as $key => $value) {
            if (! in_array((string) $key, $allowed, true)) {
                continue;
            }
            $copy[$key] = trim((string) $value);
            $changed = true;
        }

        if (! $changed) {
            return;
        }

        $setting->landing_copy = $copy;
        $setting->save();
    }
}
