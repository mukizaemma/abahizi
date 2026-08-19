@php
    $ways = $activity->normalizedInvolvementWays();
    $channelsReady = $formChannels['channels_ready'] ?? false;
    $oldWay = old('involvement_slug');
@endphp

@if(count($ways) > 0)
<div
    class="modal fade initiative-involve-modal"
    id="getInvolvedModal"
    tabindex="-1"
    aria-labelledby="initiative-involve-title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h2 id="initiative-involve-title" class="modal-title h3 mb-0">{{ __('site.initiative.cta_title') }}</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted mb-4">{{ __('site.initiative.cta_lead') }}</p>

                @if(session('involve_success'))
                    <noscript>
                        <div class="alert alert-success">{{ session('involve_success') }}</div>
                    </noscript>
                @endif
                @if($errors->any())
                    <noscript>
                        <div class="alert alert-danger">{{ __('site.initiative.swal_failed') }}. {{ __('site.initiative.swal_failed_text') }}</div>
                    </noscript>
                @endif

                @if(! $channelsReady)
                    <div class="alert alert-warning mb-0">
                        {{ __('site.initiative.form_unavailable') }}
                    </div>
                @else
                    <form
                        action="{{ route('initiativeInvolve', $activity->slug) }}"
                        method="POST"
                        class="row g-3 site-channel-form initiative-involve__form"
                        data-form-type="initiative"
                        data-msg-submitting="{{ __('site.forms.swal_submitting') }}"
                        data-msg-submitted="{{ __('site.forms.swal_submitted') }}"
                        data-msg-submitted-text="{{ __('site.forms.swal_submitted_whatsapp') }}"
                        data-msg-failed="{{ __('site.forms.swal_failed') }}"
                        data-msg-failed-text="{{ __('site.forms.swal_failed_text') }}"
                        novalidate
                    >
                        @csrf
                        <input type="hidden" name="started_at" value="{{ now()->timestamp }}">
                        <div class="site-hp-field" aria-hidden="true">
                            <label for="website_initiative_{{ $activity->id }}">Website</label>
                            <input type="text" name="website" id="website_initiative_{{ $activity->id }}" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="col-12">
                            <p class="form-label mb-2">{{ __('site.initiative.way_label') }}</p>
                            <div class="initiative-involve__ways" role="radiogroup" aria-label="{{ __('site.initiative.way_label') }}">
                                @foreach($ways as $way)
                                    <label class="initiative-involve__way">
                                        <input
                                            type="radio"
                                            name="involvement_slug"
                                            value="{{ $way['slug'] }}"
                                            class="initiative-involve__way-input"
                                            data-kind="{{ $way['kind'] }}"
                                            {{ $oldWay === $way['slug'] || ($oldWay === null && $loop->first) ? 'checked' : '' }}
                                            required
                                        >
                                        <span class="initiative-involve__way-card">
                                            <strong>{{ $way['label'] }}</strong>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12 initiative-involve__donate d-none" data-donate-fields>
                            <div class="initiative-involve__donate-box">
                                <p class="fw-semibold mb-3">{{ __('site.initiative.donate_heading') }}</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="initiative_donation_amount">{{ __('site.initiative.donate_amount') }}</label>
                                        <input type="text" name="donation_amount" id="initiative_donation_amount" class="form-control" value="{{ old('donation_amount') }}" inputmode="decimal" placeholder="{{ __('site.initiative.donate_amount_ph') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <p class="form-label mb-2">{{ __('site.initiative.donate_period') }}</p>
                                        <div class="d-flex flex-wrap gap-3">
                                            <label class="initiative-involve__period">
                                                <input type="radio" name="donation_period" value="one_time" {{ old('donation_period', 'one_time') === 'one_time' ? 'checked' : '' }}>
                                                <span>{{ __('site.initiative.donate_one_time') }}</span>
                                            </label>
                                            <label class="initiative-involve__period">
                                                <input type="radio" name="donation_period" value="recurring" {{ old('donation_period') === 'recurring' ? 'checked' : '' }}>
                                                <span>{{ __('site.initiative.donate_recurring') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="initiative_names">{{ __('site.initiative.form_name') }}</label>
                            <input type="text" name="names" id="initiative_names" class="form-control" required maxlength="255" value="{{ old('names') }}" autocomplete="name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="initiative_email">{{ __('site.initiative.form_email') }}</label>
                            <input type="email" name="email" id="initiative_email" class="form-control" required maxlength="255" value="{{ old('email') }}" autocomplete="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="initiative_phone">{{ __('site.initiative.form_phone') }}</label>
                            <input type="tel" name="phone" id="initiative_phone" class="form-control" required minlength="10" maxlength="64" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="initiative_address">{{ __('site.initiative.form_address') }}</label>
                            <input type="text" name="address" id="initiative_address" class="form-control" required maxlength="255" value="{{ old('address') }}" autocomplete="street-address">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="initiative_note">{{ __('site.initiative.form_note') }}</label>
                            <textarea name="note" id="initiative_note" class="form-control" rows="4" maxlength="20000">{{ old('note') }}</textarea>
                        </div>

                        @include('frontend.includes.form-channel-submit', [
                            'formType' => 'initiative',
                            'formSource' => 'initiative-' . $activity->slug,
                        ])
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
