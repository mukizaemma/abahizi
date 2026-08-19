<?php

namespace App\Support;

class FactoryPageContent
{
    /**
     * @return list<array{title: string, text: string, items: list<string>}>
     */
    public static function offerCards(?string $raw, array $fallback = []): array
    {
        $cards = self::decodeCards($raw);
        if ($cards !== []) {
            return $cards;
        }

        return $fallback;
    }

    /**
     * @return list<array{title: string, text: string}>
     */
    public static function processSteps(?string $raw, array $fallback = []): array
    {
        $decoded = json_decode((string) $raw, true);
        if (is_array($decoded) && $decoded !== [] && isset($decoded[0]) && is_array($decoded[0])) {
            $steps = [];
            foreach ($decoded as $row) {
                $title = trim((string) ($row['title'] ?? ''));
                $text = trim((string) ($row['text'] ?? $row['desc'] ?? ''));
                if ($title === '' && $text === '') {
                    continue;
                }
                $steps[] = [
                    'title' => $title,
                    'text' => $text,
                ];
            }
            if ($steps !== []) {
                return $steps;
            }
        }

        return $fallback;
    }

    /**
     * @param  array<int, array<string, mixed>>  $input
     */
    public static function encodeProcess(array $input): ?string
    {
        $steps = [];
        foreach ($input as $row) {
            $title = trim((string) ($row['title'] ?? ''));
            $text = trim((string) ($row['text'] ?? $row['desc'] ?? ''));
            if ($title === '' && $text === '') {
                continue;
            }
            $steps[] = [
                'title' => $title,
                'text' => $text,
            ];
        }

        if ($steps === []) {
            return null;
        }

        return json_encode($steps, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param  list<array{title?: string, text?: string, items?: list<string>}>  $rows
     * @param  array{title: string, text: string, items: list<string>}  $blank
     * @return list<array{title: string, text: string, items: list<string>}>
     */
    public static function padCards(array $rows, int $count, array $blank): array
    {
        $padded = array_values($rows);
        while (count($padded) < $count) {
            $padded[] = $blank;
        }

        return array_slice($padded, 0, $count);
    }

    /**
     * @return list<string>
     */
    public static function lines(?string $raw): array
    {
        $json = json_decode((string) $raw, true);
        if (is_array($json)) {
            $lines = [];
            foreach ($json as $row) {
                if (is_string($row) && trim($row) !== '') {
                    $lines[] = trim($row);
                } elseif (is_array($row)) {
                    $title = trim((string) ($row['title'] ?? ''));
                    $text = trim((string) ($row['text'] ?? ''));
                    if ($title !== '') {
                        $lines[] = $title;
                    } elseif ($text !== '') {
                        $lines[] = $text;
                    }
                    foreach ((array) ($row['items'] ?? []) as $item) {
                        $item = trim((string) $item);
                        if ($item !== '') {
                            $lines[] = $item;
                        }
                    }
                }
            }
            if ($lines !== []) {
                return $lines;
            }
        }

        $plain = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $raw))));
        $chunks = preg_split('/[\r\n]+|•|·|;/', (string) $raw) ?: [];
        $lines = [];
        foreach ($chunks as $chunk) {
            $line = trim(strip_tags(html_entity_decode($chunk)));
            $line = trim($line, " \t\n\r\0\x0B-•,");
            if ($line !== '') {
                $lines[] = $line;
            }
        }

        if ($lines === [] && $plain !== '') {
            $lines[] = $plain;
        }

        return array_values(array_unique($lines));
    }

    public static function plainLead(?string $html): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $html))));

        return $text;
    }

    /**
     * @param  array<int, array<string, mixed>>  $input
     */
    public static function encodeCards(array $input): ?string
    {
        $cards = [];
        foreach ($input as $row) {
            $title = trim((string) ($row['title'] ?? ''));
            $text = trim((string) ($row['text'] ?? $row['desc'] ?? ''));
            $itemsRaw = $row['items'] ?? '';
            $items = [];
            if (is_array($itemsRaw)) {
                foreach ($itemsRaw as $item) {
                    $item = trim((string) $item);
                    if ($item !== '') {
                        $items[] = $item;
                    }
                }
            } else {
                foreach (preg_split('/\r\n|\n|\r/', (string) $itemsRaw) ?: [] as $item) {
                    $item = trim($item);
                    if ($item !== '') {
                        $items[] = $item;
                    }
                }
            }
            if ($title === '' && $text === '' && $items === []) {
                continue;
            }
            $cards[] = [
                'title' => $title,
                'text' => $text,
                'items' => $items,
            ];
        }

        if ($cards === []) {
            return null;
        }

        return json_encode($cards, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return list<array{title: string, text: string, items: list<string>}>
     */
    private static function decodeCards(?string $raw): array
    {
        $decoded = json_decode((string) $raw, true);
        if (! is_array($decoded) || $decoded === []) {
            return [];
        }

        $source = $decoded;
        if (isset($decoded['cards']) && is_array($decoded['cards'])) {
            $source = $decoded['cards'];
        }

        if (! isset($source[0]) || ! is_array($source[0])) {
            return [];
        }

        $cards = [];
        foreach ($source as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            $text = trim((string) ($row['text'] ?? $row['desc'] ?? ''));
            $items = [];
            foreach ((array) ($row['items'] ?? []) as $item) {
                $item = trim((string) $item);
                if ($item !== '') {
                    $items[] = $item;
                }
            }
            if ($title === '' && $text === '' && $items === []) {
                continue;
            }
            $cards[] = [
                'title' => $title,
                'text' => $text,
                'items' => $items,
            ];
        }

        return $cards;
    }

    /**
     * @return list<string>
     */
    private static function plainLines(?string $raw): array
    {
        if (trim((string) $raw) === '') {
            return [];
        }

        $decoded = json_decode((string) $raw, true);
        if (is_array($decoded)) {
            return [];
        }

        $lines = [];
        foreach (preg_split('/[\r\n]+|•|·/', (string) $raw) ?: [] as $chunk) {
            $line = trim(strip_tags(html_entity_decode($chunk)));
            $line = trim($line, " \t\n\r\0\x0B-•,");
            if ($line !== '') {
                $lines[] = $line;
            }
        }

        return array_values(array_unique($lines));
    }
}
