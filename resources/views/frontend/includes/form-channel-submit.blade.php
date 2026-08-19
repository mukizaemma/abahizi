@php
    $formType = $formType ?? 'partnership';
    $formSource = $formSource ?? null;
    $channelsReady = $formChannels['channels_ready'] ?? false;
    $whatsappActive = $formChannels['whatsapp_active'] ?? true;
    $emailActive = $formChannels['email_active'] ?? true;
@endphp

@if(! $channelsReady)
    <div class="col-12">
        <div class="alert alert-warning mb-0">
            <strong>Form temporarily unavailable.</strong>
            An administrator must configure both a valid <strong>email</strong> and <strong>WhatsApp phone number</strong> in site settings before submissions can be recorded.
        </div>
    </div>
@else
    <div
        class="col-12 site-form-channel"
        data-msg-submitting="{{ __('site.forms.swal_submitting') }}"
        data-msg-submitted="{{ __('site.forms.swal_submitted') }}"
        data-msg-submitted-whatsapp="{{ __('site.forms.swal_submitted_whatsapp') }}"
        data-msg-submitted-email="{{ __('site.forms.swal_submitted_email') }}"
        data-msg-open-whatsapp="{{ __('site.forms.swal_open_whatsapp') }}"
        data-msg-open-email="{{ __('site.forms.swal_open_email') }}"
        data-msg-failed="{{ __('site.forms.swal_failed') }}"
        data-msg-failed-text="{{ __('site.forms.swal_failed_text') }}"
    >
        <input type="hidden" name="submission_channel" value="">
        @if($formSource)
            <input type="hidden" name="form_source" value="{{ $formSource }}">
        @endif

        <div class="site-form-channel__actions">
            <button
                type="button"
                class="site-form-channel__btn site-form-channel__btn--whatsapp"
                data-channel="whatsapp"
                @disabled(! $whatsappActive)
            >
                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                <span>{{ __('site.forms.submit_whatsapp') }}</span>
            </button>
            <button
                type="button"
                class="site-form-channel__btn site-form-channel__btn--email"
                data-channel="email"
                @disabled(! $emailActive)
            >
                <i class="far fa-envelope" aria-hidden="true"></i>
                <span>{{ __('site.forms.submit_email') }}</span>
            </button>
        </div>
    </div>
@endif
