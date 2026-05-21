<?php

namespace App\Support;

use App\Models\Setting;

class FormChannelService
{
    public const CHANNEL_WHATSAPP = 'whatsapp';

    public const CHANNEL_EMAIL = 'email';

    /**
     * @return array{
     *     whatsapp_number: string,
     *     email: ?string,
     *     whatsapp_active: bool,
     *     email_active: bool,
     *     channels_ready: bool
     * }
     */
    public static function availability(?Setting $setting): array
    {
        $whatsappNumber = self::whatsappDigits($setting);
        $email = self::normalizeEmail($setting?->email);

        $whatsappActive = strlen($whatsappNumber) >= 10;
        $emailActive = $email !== null;

        return [
            'whatsapp_number' => $whatsappNumber,
            'email' => $email,
            'whatsapp_active' => $whatsappActive,
            'email_active' => $emailActive,
            'channels_ready' => $whatsappActive && $emailActive,
        ];
    }

    public static function whatsappDigits(?Setting $setting): string
    {
        foreach ([$setting?->phone ?? '', $setting?->phone1 ?? ''] as $raw) {
            $digits = preg_replace('/\D+/', '', (string) $raw);
            if (strlen($digits) >= 10) {
                return $digits;
            }
        }

        return '';
    }

    public static function normalizeEmail(?string $email): ?string
    {
        $email = trim((string) $email);

        return filter_var($email, FILTER_VALIDATE_EMAIL) ?: null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function openUrl(string $channel, ?Setting $setting, string $formType, array $payload): ?string
    {
        $availability = self::availability($setting);

        if ($channel === self::CHANNEL_WHATSAPP) {
            if (! $availability['whatsapp_active']) {
                return null;
            }

            $text = self::buildMessage($formType, $payload);

            return 'https://wa.me/' . $availability['whatsapp_number'] . '?text=' . rawurlencode($text);
        }

        if ($channel === self::CHANNEL_EMAIL) {
            if (! $availability['email_active'] || empty($availability['email'])) {
                return null;
            }

            $subject = self::buildSubject($formType, $payload);
            $body = self::buildMessage($formType, $payload);

            return 'mailto:' . $availability['email']
                . '?subject=' . rawurlencode($subject)
                . '&body=' . rawurlencode($body);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function buildSubject(string $formType, array $payload): string
    {
        $name = trim((string) ($payload['full_name'] ?? 'Visitor'));

        return match ($formType) {
            'order' => 'Product order request — ' . $name,
            default => 'Partnership inquiry — ' . $name,
        };
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function buildMessage(string $formType, array $payload): string
    {
        if ($formType === 'order') {
            $lines = [
                'Product order request (Abahizi Rwanda website)',
                '',
                'Name: ' . trim((string) ($payload['full_name'] ?? '')),
                'Phone: ' . trim((string) ($payload['phone'] ?? '')),
                'Email: ' . trim((string) ($payload['email'] ?? '')),
            ];

            if (! empty($payload['product_reference'])) {
                $lines[] = 'Product: ' . trim((string) $payload['product_reference']);
            }

            $lines[] = '';
            $lines[] = 'Request details:';
            $lines[] = trim((string) ($payload['product_description'] ?? ''));

            return implode("\n", $lines);
        }

        $lines = [
            'Partnership / collaboration inquiry (Abahizi Rwanda website)',
            '',
            'Name: ' . trim((string) ($payload['full_name'] ?? '')),
        ];

        if (! empty($payload['organization'])) {
            $lines[] = 'Organisation: ' . trim((string) $payload['organization']);
        }

        $lines[] = 'Phone: ' . trim((string) ($payload['phone'] ?? ''));
        $lines[] = 'Email: ' . trim((string) ($payload['email'] ?? ''));

        if (! empty($payload['interests'])) {
            $lines[] = 'Interests: ' . trim((string) $payload['interests']);
        }

        if (! empty($payload['message'])) {
            $lines[] = '';
            $lines[] = 'Message:';
            $lines[] = trim((string) $payload['message']);
        }

        return implode("\n", $lines);
    }

    public static function channelLabel(?string $channel): string
    {
        return match ($channel) {
            self::CHANNEL_WHATSAPP => 'WhatsApp',
            self::CHANNEL_EMAIL => 'Email',
            default => '—',
        };
    }
}
