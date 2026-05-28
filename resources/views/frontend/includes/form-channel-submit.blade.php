@php
    $formType = $formType ?? 'partnership';
    $formSource = $formSource ?? null;
    $channelsReady = $formChannels['channels_ready'] ?? false;
@endphp

@if(! $channelsReady)
    <div class="col-12">
        <div class="alert alert-warning mb-0">
            <strong>Form temporarily unavailable.</strong>
            An administrator must configure both a valid <strong>email</strong> and <strong>WhatsApp phone number</strong> in site settings before submissions can be recorded.
        </div>
    </div>
@else
    <div class="col-12 site-form-channel">
        <div class="site-form-channel__choices row g-2 mb-3" role="radiogroup" aria-label="Send via WhatsApp or email">
            <div class="col-sm-6">
                <label class="site-form-channel__choice">
                    <input type="radio" name="submission_channel_choice" value="whatsapp" class="site-form-channel__radio" required>
                    <span class="site-form-channel__choice-inner">
                        <i class="fab fa-whatsapp site-form-channel__icon site-form-channel__icon--wa" aria-hidden="true"></i>
                        <span class="site-form-channel__choice-label">WhatsApp</span>
                    </span>
                </label>
            </div>
            <div class="col-sm-6">
                <label class="site-form-channel__choice">
                    <input type="radio" name="submission_channel_choice" value="email" class="site-form-channel__radio" required>
                    <span class="site-form-channel__choice-inner">
                        <i class="far fa-envelope site-form-channel__icon" aria-hidden="true"></i>
                        <span class="site-form-channel__choice-label">Email</span>
                    </span>
                </label>
            </div>
        </div>

        <button type="button" class="btn btn-lg fw-semibold text-dark site-form-submit site-form-channel__open" disabled>
            Submit
        </button>

        <div class="site-form-channel__confirm mt-4 d-none" aria-live="polite">
            <div class="alert alert-info mb-3">
                A new tab should have opened with your message ready. Send it in WhatsApp or your email app, then confirm here so we can record your submission.
            </div>
            <input type="hidden" name="submission_channel" value="">
            <input type="hidden" name="channel_confirmed" value="">
            <input type="hidden" name="channel_token" value="">
            @if($formSource)
                <input type="hidden" name="form_source" value="{{ $formSource }}">
            @endif
            <button type="submit" class="btn btn-lg fw-semibold text-dark site-form-submit site-form-channel__confirm-btn" disabled>
                I sent the message — save my submission
            </button>
            <button type="button" class="btn btn-outline-secondary btn-lg ms-0 ms-md-2 mt-2 mt-md-0 site-form-channel__retry">
                Choose another option
            </button>
        </div>
    </div>
@endif
