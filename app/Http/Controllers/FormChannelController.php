<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\FormChannelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FormChannelController extends Controller
{
    public function intent(Request $request): JsonResponse
    {
        $setting = Setting::firstOrEmpty();
        $availability = FormChannelService::availability($setting);

        if (! $availability['channels_ready']) {
            throw ValidationException::withMessages([
                'form' => 'Submissions are unavailable until both a valid site email and WhatsApp number are configured in admin settings.',
            ]);
        }

        $validated = $request->validate([
            'submission_channel' => ['required', 'in:whatsapp,email'],
            'form_type' => ['required', 'in:partnership,order,contact'],
        ]);

        $channel = $validated['submission_channel'];
        $formType = $validated['form_type'];

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

        $payload = match ($formType) {
            'order' => $this->validateOrderPayload($request),
            'contact' => $this->validateContactPayload($request),
            default => $this->validatePartnershipPayload($request),
        };

        $openUrl = FormChannelService::openUrl($channel, $setting, $formType, $payload);

        if ($openUrl === null) {
            throw ValidationException::withMessages([
                'form' => 'Unable to open the selected contact channel. Please try again later.',
            ]);
        }

        $token = Str::random(40);

        Cache::put('form_submit:' . $token, [
            'channel' => $channel,
            'form_type' => $formType,
            'ip' => $request->ip(),
            'opened_at' => time(),
        ], now()->addMinutes(30));

        return response()->json([
            'token' => $token,
            'open_url' => $openUrl,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePartnershipPayload(Request $request): array
    {
        $allowed = [
            'training',
            'equipment',
            'fundraising',
            'volunteering',
            'sales_ambassador',
            'wholesale',
            'corporate',
            'other',
        ];

        $validated = $request->validate([
            'organization' => ['nullable', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'in:' . implode(',', $allowed)],
            'message' => ['nullable', 'string', 'max:20000'],
        ]);

        if (empty($request->input('interests')) && ! $request->filled('message')) {
            throw ValidationException::withMessages([
                'interests' => 'Select at least one area of interest or write a message.',
            ]);
        }

        $labels = [
            'training' => 'Skills development & training',
            'equipment' => 'Equipment or materials',
            'fundraising' => 'Fundraising or sponsorship',
            'volunteering' => 'Volunteering',
            'sales_ambassador' => 'Sales & ambassador programmes',
            'wholesale' => 'Wholesale / bulk orders',
            'corporate' => 'Corporate or institutional partnership',
            'other' => 'Other',
        ];

        $raw = (array) $request->input('interests', []);
        $picked = array_values(array_intersect($allowed, $raw));
        $summaryParts = [];
        foreach ($picked as $key) {
            $summaryParts[] = $labels[$key] ?? $key;
        }

        $validated['interests'] = $summaryParts !== [] ? implode(', ', $summaryParts) : null;

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateOrderPayload(Request $request): array
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:255'],
            'product_description' => ['required', 'string', 'max:20000'],
            'product_slug' => ['nullable', 'string', 'max:255'],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99999'],
        ]);

        $phoneDigits = preg_replace('/\D+/', '', (string) $validated['phone']);
        if (strlen($phoneDigits) < 10) {
            throw ValidationException::withMessages([
                'phone' => 'Enter a valid phone number with at least 10 digits.',
            ]);
        }

        if (FormChannelService::normalizeEmail($validated['email']) === null) {
            throw ValidationException::withMessages([
                'email' => 'Enter a valid email address.',
            ]);
        }

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateContactPayload(Request $request): array
    {
        $allowed = array_keys(FormChannelService::contactInterestLabels());

        $validated = $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'in:' . implode(',', $allowed)],
            'message' => ['required', 'string', 'min:10', 'max:20000'],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'max:0'],
        ]);

        $phoneDigits = preg_replace('/\D+/', '', (string) $validated['phone']);
        if (strlen($phoneDigits) < 10) {
            throw ValidationException::withMessages([
                'phone' => 'Enter a valid phone number with at least 10 digits.',
            ]);
        }

        if (FormChannelService::normalizeEmail($validated['email']) === null) {
            throw ValidationException::withMessages([
                'email' => 'Enter a valid email address.',
            ]);
        }

        foreach (['names', 'organization', 'message'] as $field) {
            $value = (string) ($validated[$field] ?? '');
            if (FormChannelService::containsSpamLinks($value)) {
                throw ValidationException::withMessages([
                    $field => 'Please remove links from this field.',
                ]);
            }
        }

        $validated['interests'] = FormChannelService::formatContactInterests((array) $request->input('interests', []));

        return $validated;
    }
}
