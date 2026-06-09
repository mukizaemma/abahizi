<?php

namespace App\Support;

class HowItWorks
{
    /**
     * @return array{title: string, lead: string, body: string}
     */
    public static function parseIntro(?string $html): array
    {
        $defaults = [
            'title' => 'Manufacturing with purpose',
            'lead' => 'We manufacture high-quality handbags and accessories for international fashion brands through a structured and collaborative production process.',
            'body' => '',
        ];

        if ($html === null || trim($html) === '') {
            return $defaults;
        }

        $working = $html;
        $working = static::stripHowItWorksSection($working);

        $title = $defaults['title'];
        if (preg_match('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $working, $match)) {
            $candidate = trim(html_entity_decode(strip_tags($match[1])));
            if ($candidate !== '') {
                $title = $candidate;
            }
            $working = preg_replace('/<h[1-6][^>]*>.*?<\/h[1-6]>/is', '', $working, 1);
        }

        $lead = '';
        if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $working, $match)) {
            $lead = trim(html_entity_decode(strip_tags($match[1])));
            $working = preg_replace('/<p[^>]*>.*?<\/p>/is', '', $working, 1);
        }

        if ($lead === '') {
            $plain = trim(html_entity_decode(strip_tags($working)));
            $lines = preg_split('/\r\n|\r|\n/', $plain);
            $lines = array_values(array_filter(array_map('trim', $lines), static fn (string $line): bool => $line !== ''));
            if ($lines !== []) {
                $lead = array_shift($lines);
                $working = implode("\n", $lines);
            }
        }

        $body = trim($working);
        if ($lead === '') {
            $lead = $defaults['lead'];
        }

        return [
            'title' => $title,
            'lead' => $lead,
            'body' => $body,
        ];
    }

    /**
     * @return list<array{title: string, desc: string}>
     */
    public static function parseSteps(?string $html): array
    {
        if ($html === null || trim($html) === '') {
            return [];
        }

        $section = static::extractHowItWorksSection($html);

        return static::parseStepsFromMarkup($section);
    }

    /**
     * @return list<array{title: string, desc: string}>
     */
    public static function fallbackSteps(): array
    {
        return static::defaultSteps();
    }

    /**
     * @return list<array{title: string, desc: string}>
     */
    protected static function parseStepsFromMarkup(string $html): array
    {
        $steps = [];

        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $matches)) {
            foreach ($matches[1] as $rawItem) {
                $step = static::splitTitleAndDesc($rawItem);
                if ($step !== null) {
                    $steps[] = $step;
                }
            }
        }

        if ($steps !== []) {
            return $steps;
        }

        if (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $matches)) {
            foreach ($matches[1] as $rawParagraph) {
                if (! preg_match('/<(strong|b)[^>]*>/i', $rawParagraph)) {
                    continue;
                }

                $step = static::splitTitleAndDesc($rawParagraph);
                if ($step !== null) {
                    $steps[] = $step;
                }
            }
        }

        if ($steps !== []) {
            return $steps;
        }

        $plain = trim(html_entity_decode(strip_tags($html)));
        $lines = preg_split('/\r\n|\r|\n/', $plain);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || static::isHowItWorksHeading($line)) {
                continue;
            }

            $step = static::splitPlainLine($line);
            if ($step !== null) {
                $steps[] = $step;
            }
        }

        return $steps;
    }

    protected static function extractHowItWorksSection(string $html): string
    {
        if (preg_match('/<h[1-6][^>]*>\s*how\s+it\s+works\s*<\/h[1-6]>(.*)$/is', $html, $match)) {
            return trim($match[1]);
        }

        if (preg_match('/how\s+it\s+works\s*:?\s*(.+)$/is', html_entity_decode(strip_tags($html)), $match)) {
            return trim($match[1]);
        }

        return $html;
    }

    protected static function stripHowItWorksSection(string $html): string
    {
        if (preg_match('/^(.*?)<h[1-6][^>]*>\s*how\s+it\s+works\s*<\/h[1-6]>/is', $html, $match)) {
            return trim($match[1]);
        }

        return $html;
    }

    /**
     * @return ?array{title: string, desc: string}
     */
    protected static function splitTitleAndDesc(string $raw): ?array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }

        if (preg_match('/<(strong|b)[^>]*>(.*?)<\/\1>\s*:?\s*(.*)$/is', $raw, $match)) {
            $title = trim(html_entity_decode(strip_tags($match[2])));
            $desc = trim(html_entity_decode(strip_tags($match[3])));

            if ($title !== '' && $desc !== '') {
                return ['title' => rtrim($title, ':'), 'desc' => $desc];
            }
        }

        return static::splitPlainLine(trim(html_entity_decode(strip_tags($raw))));
    }

    /**
     * @return ?array{title: string, desc: string}
     */
    protected static function splitPlainLine(string $line): ?array
    {
        $line = trim($line, " \t-•");
        if ($line === '' || static::isHowItWorksHeading($line)) {
            return null;
        }

        if (preg_match('/^(.+?)\s*:\s*(.+)$/', $line, $match)) {
            return [
                'title' => trim($match[1]),
                'desc' => trim($match[2]),
            ];
        }

        if (preg_match('/^(.+?)\s*[—–-]\s*(.+)$/', $line, $match)) {
            return [
                'title' => trim($match[1]),
                'desc' => trim($match[2]),
            ];
        }

        return [
            'title' => $line,
            'desc' => '',
        ];
    }

    protected static function isHowItWorksHeading(string $text): bool
    {
        return (bool) preg_match('/^how\s+it\s+works$/i', trim($text));
    }

    /**
     * @return list<array{title: string, desc: string}>
     */
    protected static function defaultSteps(): array
    {
        return [
            [
                'title' => 'Client concept submission',
                'desc' => 'Clients share design ideas, concepts, or references.',
            ],
            [
                'title' => 'Design & sampling',
                'desc' => 'Our technical team develops prototypes and samples based on the client\'s vision.',
            ],
            [
                'title' => 'Feedback & refinement',
                'desc' => 'We collaborate closely with clients to refine designs until they meet exact specifications.',
            ],
            [
                'title' => 'Production',
                'desc' => 'Once approved, our factory employees produce at scale with precision and consistency.',
            ],
            [
                'title' => 'Quality control & delivery',
                'desc' => 'Each product undergoes strict quality checks before being prepared for export and delivery.',
            ],
        ];
    }
}
