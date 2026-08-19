<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Setting;
use App\Support\FormChannelService;
use Illuminate\Http\Request;
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

        return [
            'channel' => $channel,
            'form_type' => $expectedFormType,
        ];
    }

    /**
     * @param  array<string, string>  $errors
     */
    protected function channelSubmitFail(Request $request, array $errors)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => reset($errors) ?: 'Unable to submit your request.',
                'errors' => collect($errors)->map(fn ($msg) => [$msg])->all(),
            ], 422);
        }

        return back()->withInput()->withErrors($errors);
    }

    protected function channelSubmitOk(Request $request, string $success, ?string $openUrl, string $redirectUrl, string $flashKey = 'success')
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $success,
                'open_url' => $openUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with($flashKey, $success);
    }
}
