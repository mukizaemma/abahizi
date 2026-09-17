<?php

namespace App\Support;

use App\Models\Background;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ImpactStats
{
    /**
     * @return array<int, array{value: string, label: string, show_on_bar: bool}>
     */
    public static function items(?Background $about = null): array
    {
        $about ??= Background::firstOrEmpty();
        $stored = static::stored($about);
        if ($stored !== []) {
            return $stored;
        }

        return static::defaults($about);
    }

    /**
     * Stats shown on the dark homepage banner strip.
     *
     * @return array<int, array{value: string, label: string, show_on_bar: bool}>
     */
    public static function forHeroBar(?Background $about = null): array
    {
        $items = static::items($about);
        $onBar = array_values(array_filter($items, fn ($item) => ! empty($item['show_on_bar'])));

        return $onBar !== [] ? $onBar : $items;
    }

    public static function counterTarget(string $value): int
    {
        $digits = preg_replace('/\D/', '', $value);

        return $digits !== '' ? (int) $digits : 0;
    }

    /**
     * @return array<int, array{value: string, label: string, show_on_bar: bool}>
     */
    public static function stored(?Background $about = null): array
    {
        $about ??= Background::firstOrEmpty();
        if (! Schema::hasColumn('backgrounds', 'impact_stats')) {
            return [];
        }

        $raw = $about->impact_stats ?? null;
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : [];
        }
        if (! is_array($raw)) {
            return [];
        }

        $items = [];
        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }
            $value = trim((string) ($row['value'] ?? ''));
            $label = trim((string) ($row['label'] ?? ''));
            if ($value === '' && $label === '') {
                continue;
            }
            $items[] = [
                'value' => $value,
                'label' => $label,
                'show_on_bar' => (bool) ($row['show_on_bar'] ?? true),
            ];
        }

        return $items;
    }

    /**
     * @return array<int, array{value: string, label: string, show_on_bar: bool}>
     */
    public static function defaults(?Background $about = null): array
    {
        $about ??= new Background();

        $rows = [
            [
                'value' => trim((string) ($about->handbags_exported ?? '')) ?: '310,000+',
                'label' => __('site.stats.handbags'),
                'show_on_bar' => true,
            ],
            [
                'value' => trim((string) ($about->artisans_count ?? ''))
                    ?: (trim((string) ($about->jobs_created ?? '')) ?: '260+'),
                'label' => __('site.stats.artisans'),
                'show_on_bar' => true,
            ],
            [
                'value' => trim((string) ($about->families_impacted ?? '')) ?: '2,000+',
                'label' => __('site.stats.families'),
                'show_on_bar' => true,
            ],
            [
                'value' => trim((string) ($about->training_hours ?? '')) ?: '20,000+',
                'label' => __('site.stats.training'),
                'show_on_bar' => true,
            ],
        ];

        $jobs = trim((string) ($about->jobs_created ?? ''));
        $artisans = trim((string) ($about->artisans_count ?? ''));
        if ($jobs !== '' && $jobs !== $artisans) {
            $rows[] = [
                'value' => $jobs,
                'label' => __('site.landing.stat_jobs'),
                'show_on_bar' => false,
            ];
        }

        return $rows;
    }

    public static function saveFromRequest(Request $request, Background $data): void
    {
        if (! $request->exists('stat_value')) {
            return;
        }

        $values = $request->input('stat_value', []);
        $labels = $request->input('stat_label', []);
        $onBar = $request->input('stat_on_bar', []);
        if (! is_array($values)) {
            $values = [];
        }
        if (! is_array($labels)) {
            $labels = [];
        }
        if (! is_array($onBar)) {
            $onBar = [];
        }

        $items = [];
        foreach ($values as $index => $value) {
            $value = trim((string) $value);
            $label = trim((string) ($labels[$index] ?? ''));
            if ($value === '' && $label === '') {
                continue;
            }
            $items[] = [
                'value' => $value,
                'label' => $label !== '' ? $label : $value,
                'show_on_bar' => (string) ($onBar[$index] ?? '1') === '1',
            ];
        }

        if (Schema::hasColumn('backgrounds', 'impact_stats')) {
            $data->impact_stats = json_encode($items, JSON_UNESCAPED_UNICODE);
        }

        static::syncLegacyColumns($data, $items);
    }

    /**
     * @param  array<int, array{value: string, label: string, show_on_bar?: bool}>  $items
     */
    protected static function syncLegacyColumns(Background $data, array $items): void
    {
        $assignments = [
            'handbags_exported' => null,
            'artisans_count' => null,
            'families_impacted' => null,
            'training_hours' => null,
            'jobs_created' => null,
        ];

        foreach ($items as $item) {
            $label = strtolower($item['label']);
            $value = $item['value'];
            if ($value === '') {
                continue;
            }
            if (str_contains($label, 'handbag') || str_contains($label, 'export')) {
                $assignments['handbags_exported'] = $value;
            } elseif (str_contains($label, 'famil')) {
                $assignments['families_impacted'] = $value;
            } elseif (str_contains($label, 'train')) {
                $assignments['training_hours'] = $value;
            } elseif (str_contains($label, 'job')) {
                $assignments['jobs_created'] = $value;
            } elseif (str_contains($label, 'employee') || str_contains($label, 'artisan')) {
                $assignments['artisans_count'] = $value;
            }
        }

        foreach ($assignments as $column => $value) {
            if ($value !== null && Schema::hasColumn('backgrounds', $column)) {
                $data->{$column} = $value;
            }
        }
    }
}
