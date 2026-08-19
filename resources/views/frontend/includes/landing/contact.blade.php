@php
    $channelsReady = $formChannels['channels_ready'] ?? false;
    $contactPhone = $setting->phone ?? $setting->phone1 ?? '';
    $contactEmail = $setting->email ?? '';
    $contactAddress = $setting->address ?? 'Masoro, Rulindo District, Rwanda';

    $asideImage = null;
    if (! empty($about->factory_services_image ?? null)) {
        $asideImage = asset('storage/images/' . ltrim($about->factory_services_image, '/'));
    } elseif (isset($slides) && collect($slides)->first(fn ($s) => ! empty($s->image))) {
        $slide = collect($slides)->first(fn ($s) => ! empty($s->image));
        $asideImage = \App\Models\Slide::publicImageUrl($slide->image);
    } else {
        $asideImage = asset('assets/img/slider/slider-bg-3-1.jpg');
    }
@endphp

<section class="lh-contact" id="lh-contact" aria-labelledby="lh-contact-title">
    <div class="container">
        <h2 id="lh-contact-title" class="lh-contact__title lh-reveal">{{ __('site.landing.contact_title') }}</h2>
        <p class="lh-contact__lead lh-reveal">{{ __('site.landing.contact_lead') }}</p>

        <div class="lh-contact__grid">
            <div class="lh-contact__form lh-reveal">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(! $channelsReady)
                    <div class="alert alert-warning mb-0">
                        <strong>Form temporarily unavailable.</strong>
                        An administrator must configure a valid email and WhatsApp number in site settings.
                    </div>
                @else
                    <form action="{{ route('sendMessage') }}" method="POST" class="row g-3 site-channel-form site-partner-form" data-form-type="contact" novalidate>
                        @csrf
                        <input type="hidden" name="started_at" value="{{ now()->timestamp }}">
                        <div class="site-hp-field" aria-hidden="true">
                            <label for="website_home_contact">Website</label>
                            <input type="text" name="website" id="website_home_contact" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="lh_names">{{ __('site.landing.form_name') }}</label>
                            <input type="text" name="names" id="lh_names" class="form-control" required maxlength="255" value="{{ old('names') }}" autocomplete="name" placeholder="{{ __('site.landing.form_name_ph') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="lh_email">{{ __('site.landing.form_email') }}</label>
                            <input type="email" name="email" id="lh_email" class="form-control" required maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="you@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="lh_phone">{{ __('site.landing.form_phone') }}</label>
                            <input type="tel" name="phone" id="lh_phone" class="form-control" required minlength="10" maxlength="64" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" pattern="[\d\s\+\-\(\)]{10,}" placeholder="+250 …">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="lh_message">{{ __('site.landing.form_message') }}</label>
                            <textarea name="message" id="lh_message" class="form-control" rows="5" required minlength="10" maxlength="20000" placeholder="{{ __('site.landing.form_message_ph') }}">{{ old('message') }}</textarea>
                        </div>

                        @include('frontend.includes.form-channel-submit', [
                            'formType' => 'contact',
                            'formSource' => 'home-landing',
                        ])
                    </form>
                @endif
            </div>

            <aside class="lh-contact__aside lh-reveal">
                <img src="{{ $asideImage }}" alt="{{ __('site.landing.visit_alt') }}" loading="lazy" decoding="async">
                <div class="lh-contact__aside-overlay" aria-hidden="true"></div>
                <div class="lh-contact__aside-body">
                    <h3 class="lh-contact__aside-title">{{ __('site.landing.visit_title') }}</h3>
                    <ul class="lh-contact__aside-list">
                        @if($contactPhone !== '')
                            <li>
                                <i class="fas fa-phone-alt" aria-hidden="true"></i>
                                <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                            </li>
                        @endif
                        @if($contactEmail !== '')
                            <li>
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                            </li>
                        @endif
                        <li>
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span>{{ $contactAddress }}</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<div class="lh-modal" id="lh-video-modal" hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="{{ __('site.landing.watch_story') }}">
    <div class="lh-modal__backdrop" data-lh-video-close></div>
    <div class="lh-modal__dialog">
        <button type="button" class="lh-modal__close" data-lh-video-close aria-label="Close video">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <div class="lh-modal__frame" data-lh-video-frame></div>
    </div>
</div>
