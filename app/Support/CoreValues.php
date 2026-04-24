<?php

namespace App\Support;

class CoreValues
{
    /**
     * Build a flat list of core value strings for grid display.
     * Priority: newline-separated list field, then &lt;li&gt; in HTML, then multi-line plain text.
     *
     * @return list<string>
     */
    public static function parseItems(?string $linesField, ?string $valuesHtml): array
    {
        if ($linesField !== null && trim($linesField) !== '') {
            $parts = preg_split('/\r\n|\r|\n/', $linesField);

            return array_values(array_filter(array_map(static function ($line) {
                return trim(html_entity_decode(strip_tags($line)));
            }, $parts), static fn (string $s): bool => $s !== ''));
        }

        if ($valuesHtml === null || trim($valuesHtml) === '') {
            return [];
        }

        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $valuesHtml, $m)) {
            return array_values(array_filter(array_map(static function ($raw) {
                return trim(html_entity_decode(strip_tags($raw)));
            }, $m[1]), static fn (string $s): bool => $s !== ''));
        }

        $plain = trim(html_entity_decode(strip_tags($valuesHtml)));
        $lines = preg_split('/\r\n|\r|\n/', $plain);
        $lines = array_values(array_filter(array_map('trim', $lines), static fn (string $s): bool => $s !== ''));

        return count($lines) > 1 ? $lines : [];
    }
}
