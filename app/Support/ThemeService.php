<?php

namespace App\Support;

use App\Models\Setting;

class ThemeService
{
    public const DEFAULT_PRIMARY = '#fad200';

    public const DEFAULT_SECONDARY = '#000000';

    public const DEFAULT_NEUTRAL = '#9a9a9a';

    public const DEFAULT_BODY_FONT = 'DM Sans';

    public const DEFAULT_HEADING_FONT = 'Cormorant Garamond';

    /**
     * @return array<string, string>
     */
    public static function bodyFonts(): array
    {
        return [
            'DM Sans' => 'DM Sans',
            'Outfit' => 'Outfit',
            'Poppins' => 'Poppins',
            'Inter' => 'Inter',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Nunito Sans' => 'Nunito Sans',
            'Source Sans 3' => 'Source Sans 3',
            'Work Sans' => 'Work Sans',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'Roboto' => 'Roboto',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headingFonts(): array
    {
        return [
            'Cormorant Garamond' => 'Cormorant Garamond',
            'Playfair Display' => 'Playfair Display',
            'Fraunces' => 'Fraunces',
            'Libre Baskerville' => 'Libre Baskerville',
            'Merriweather' => 'Merriweather',
            'Lora' => 'Lora',
            'EB Garamond' => 'EB Garamond',
            'DM Serif Display' => 'DM Serif Display',
            'Source Serif 4' => 'Source Serif 4',
            'Outfit' => 'Outfit',
            'DM Sans' => 'DM Sans',
        ];
    }

    /**
     * @return list<string>
     */
    public static function allowedFonts(): array
    {
        return array_values(array_unique(array_merge(
            array_keys(self::bodyFonts()),
            array_keys(self::headingFonts())
        )));
    }

    public static function sanitizeFont(?string $name, string $fallback): string
    {
        $name = trim((string) $name);
        if ($name !== '' && in_array($name, self::allowedFonts(), true)) {
            return $name;
        }

        return $fallback;
    }

    public static function sanitizeHex(?string $hex, string $fallback): string
    {
        $hex = trim((string) $hex);
        if (preg_match('/^#([A-Fa-f0-9]{6})$/', $hex)) {
            return strtolower($hex);
        }

        return $fallback;
    }

    public static function onPrimaryText(string $hex): string
    {
        $hex = ltrim(self::sanitizeHex($hex, self::DEFAULT_PRIMARY), '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $luma = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luma > 0.55 ? '#111111' : '#ffffff';
    }

    public static function hexToRgbChannels(string $hex): string
    {
        $hex = ltrim(self::sanitizeHex($hex, self::DEFAULT_PRIMARY), '#');

        return hexdec(substr($hex, 0, 2)) . ', ' . hexdec(substr($hex, 2, 2)) . ', ' . hexdec(substr($hex, 4, 2));
    }

    public static function googleFontsHref(string $bodyFont, string $headingFont): string
    {
        $families = [];
        foreach (array_unique([$bodyFont, $headingFont]) as $font) {
            $families[] = 'family=' . str_replace(' ', '+', $font) . ':ital,wght@0,300;0,400;0,500;0,600;0,700;1,400';
        }

        return 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
    }

    /**
     * @return array{
     *     primary: string,
     *     secondary: string,
     *     neutral: string,
     *     on_primary: string,
     *     primary_rgb: string,
     *     body_font: string,
     *     heading_font: string,
     *     fonts_href: string
     * }
     */
    public static function fromSetting(?Setting $setting): array
    {
        $primary = self::sanitizeHex($setting?->primary_color ?? null, self::DEFAULT_PRIMARY);
        $secondary = self::sanitizeHex($setting?->secondary_color ?? null, self::DEFAULT_SECONDARY);
        $neutral = self::sanitizeHex($setting?->neutral_color ?? null, self::DEFAULT_NEUTRAL);
        $bodyFont = self::sanitizeFont($setting?->font_family ?? null, self::DEFAULT_BODY_FONT);
        $headingFont = self::sanitizeFont($setting?->heading_font ?? null, self::DEFAULT_HEADING_FONT);

        return [
            'primary' => $primary,
            'secondary' => $secondary,
            'neutral' => $neutral,
            'on_primary' => self::onPrimaryText($primary),
            'primary_rgb' => self::hexToRgbChannels($primary),
            'body_font' => $bodyFont,
            'heading_font' => $headingFont,
            'fonts_href' => self::googleFontsHref($bodyFont, $headingFont),
        ];
    }
}
