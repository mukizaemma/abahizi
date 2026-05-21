<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Setting;
use App\Support\FormChannelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

trait ValidatesFormChannelSubmission
{
    /**
     * @return array{channel: string, form_type: string}
     */
    protected function validateFormChannelGate(Request $request, string $expectedFormType): array
    {
        $availability = FormChannelService::availability(Setting::firstOrEmpty());

        if (! $availability['channels_ready']) {
            throw ValidationException::withMessages([
                'form' => 'Submissions are unavailable until both a valid site email and WhatsApp number are configured in admin settings.',
            ]);
        }

        $validated = $request->validate([
            'submission_channel' => ['required', 'in:whatsapp,email'],
            'channel_confirmed' => ['required', 'in:1'],
            'channel_token' => ['required', 'string', 'size:40'],
        ]);

        $channel = $validated['submission_channel'];

        if ($channel === FormChannelService::CHANNEL_WHATSAPP && ! $availability['whatsapp_active']) {
            throw ValidationException::withMessages([
                'submission_channel' => 'WhatsApp is not configured on this site.',
            ]);
        }

        if ($channel === FormChannelService::CHANNEL_EMAIL && ! $availability['email_active']) {
            throw ValidationException::withMessages([
                'submission_channel' => 'Email is not configured on this site.',
            ]);
        }

        $cacheKey = 'form_submit:' . $validated['channel_token'];
        $gate = Cache::pull($cacheKey);

        if (! is_array($gate)) {
            throw ValidationException::withMessages([
                'form' => 'Please open WhatsApp or email, send your message, then confirm below. Your session may have expired.',
            ]);
        }

        if (($gate['ip'] ?? null) !== $request->ip()) {
            throw ValidationException::withMessages([
                'form' => 'Could not verify your submission. Please try again.',
            ]);
        }

        if (($gate['channel'] ?? null) !== $channel) {
            throw ValidationException::withMessages([
                'submission_channel' => 'The selected channel does not match your opened app.',
            ]);
        }

        if (($gate['form_type'] ?? null) !== $expectedFormType) {
            throw ValidationException::withMessages([
                'form' => 'Invalid form session. Please open the app again and confirm.',
            ]);
        }

        $openedAt = (int) ($gate['opened_at'] ?? 0);
        if ($openedAt <= 0 || (time() - $openedAt) < 3) {
            throw ValidationException::withMessages([
                'form' => 'Please send your message in WhatsApp or email before confirming.',
            ]);
        }

        return [
            'channel' => $channel,
            'form_type' => $expectedFormType,
        ];
    }
}
