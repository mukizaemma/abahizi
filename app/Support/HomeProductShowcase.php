<?php

namespace App\Support;

use App\Models\Background;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeProductShowcase
{
    /**
     * @return array<int, string>
     */
    public static function defaultTitles(): array
    {
        return [
            1 => (string) __('site.landing.product_1_title'),
            2 => (string) __('site.landing.product_2_title'),
            3 => (string) __('site.landing.product_3_title'),
        ];
    }

    /**
     * Dedicated homepage cards. Empty slots are omitted.
     *
     * @return array<int, array{slot: int, title: string, src: string, filename: string}>
     */
    public static function cards(?Background $about = null): array
    {
        $about ??= Background::firstOrEmpty();
        $defaults = static::defaultTitles();
        $cards = [];

        foreach ([1, 2, 3] as $slot) {
            $imageField = static::imageField($slot);
            $titleField = static::titleField($slot);
            if (! Schema::hasColumn('backgrounds', $imageField)) {
                continue;
            }

            $filename = trim((string) ($about->{$imageField} ?? ''));
            if ($filename === '') {
                continue;
            }

            $src = SectionBackgroundService::urlFromFilename($filename);
            if (! $src || SectionBackgroundService::isPlaceholderUrl($src)) {
                continue;
            }

            $title = trim((string) ($about->{$titleField} ?? ''));
            if ($title === '') {
                $title = $defaults[$slot] ?? ('Card ' . $slot);
            }

            $cards[] = [
                'slot' => $slot,
                'title' => $title,
                'src' => $src,
                'filename' => $filename,
            ];
        }

        return $cards;
    }

    /**
     * @return array<int, array{slot: int, title: string, src: ?string, filename: ?string}>
     */
    public static function adminSlots(?Background $about = null): array
    {
        $about ??= Background::firstOrEmpty();
        $defaults = static::defaultTitles();
        $slots = [];

        foreach ([1, 2, 3] as $slot) {
            $imageField = static::imageField($slot);
            $titleField = static::titleField($slot);
            $filename = Schema::hasColumn('backgrounds', $imageField)
                ? trim((string) ($about->{$imageField} ?? ''))
                : '';
            $title = Schema::hasColumn('backgrounds', $titleField)
                ? trim((string) ($about->{$titleField} ?? ''))
                : '';

            $slots[] = [
                'slot' => $slot,
                'title' => $title,
                'placeholder' => $defaults[$slot] ?? '',
                'src' => $filename !== '' ? SectionBackgroundService::urlFromFilename($filename) : null,
                'filename' => $filename !== '' ? $filename : null,
                'image_field' => $imageField,
                'title_field' => $titleField,
            ];
        }

        return $slots;
    }

    public static function save(Request $request, Background $about): void
    {
        foreach ([1, 2, 3] as $slot) {
            $titleField = static::titleField($slot);
            $imageField = static::imageField($slot);

            if (Schema::hasColumn('backgrounds', $titleField) && $request->has($titleField)) {
                $title = trim((string) $request->input($titleField));
                $about->{$titleField} = $title !== '' ? Str::limit($title, 80, '') : null;
            }

            if (! Schema::hasColumn('backgrounds', $imageField)) {
                continue;
            }

            if ($request->boolean('clear_' . $imageField)) {
                static::deleteStored($about->{$imageField} ?? null);
                $about->{$imageField} = null;
                continue;
            }

            if (! $request->hasFile($imageField)) {
                continue;
            }

            static::deleteStored($about->{$imageField} ?? null);
            $file = $request->file($imageField);
            $filename = 'home_card_' . $slot . '_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('images', $filename, 'public');
            $about->{$imageField} = $filename;
        }

        $about->save();
    }

    public static function imageField(int $slot): string
    {
        return 'home_product_card_' . $slot . '_image';
    }

    public static function titleField(int $slot): string
    {
        return 'home_product_card_' . $slot . '_title';
    }

    protected static function deleteStored(?string $filename): void
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return;
        }

        if (Storage::disk('public')->exists('images/' . $filename)) {
            Storage::disk('public')->delete('images/' . $filename);
        }
    }
}
