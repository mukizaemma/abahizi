@extends('layouts.frontbase')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => __('site.nav.contact'),
        'caption' => 'One place for all inquiries — custom bags, partnerships, orders, or general questions. Send via WhatsApp or email.',
        'compact' => true,
    ])

    @php
        $mapEmbedRaw = trim((string) ($setting->google_map_embed_code ?? ''));
        $mapSrc = '';
        $mapIframeHtml = '';

        if ($mapEmbedRaw !== '') {
            if (stripos($mapEmbedRaw, '<iframe') !== false) {
                $mapIframeHtml = $mapEmbedRaw;
            } else {
                $mapSrc = $mapEmbedRaw;
            }
        }

        $defaultMapSrc = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.434670504606!2d30.1565774!3d-1.9806325999999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca75ea871adfd%3A0x807deb18c1a0592f!2sImpact%20Life%20Mission!5e0!3m2!1sen!2srw!4v1755602240867!5m2!1sen!2srw';
        $resolvedMapSrc = $mapSrc !== '' ? $mapSrc : $defaultMapSrc;
        $productReference = $product->title ?? old('product_reference');
        $channelsReady = $formChannels['channels_ready'] ?? false;
        $contactInterestOptions = \App\Support\FormChannelService::contactInterestLabels();
        $oldInterests = (array) old('interests', []);
    @endphp

    <section class="contact-page-shell pt-40 pb-50 grey-bg">
        <div class="container">
            <div class="row g-3 contact-page-shell__stats mb-4 mb-lg-5">
                <div class="col-md-4">
                    <article class="contact-stat-card h-100">
                        <span class="contact-stat-card__icon"><i class="flaticon-phone"></i></span>
                        <div>
                            <h3 class="contact-stat-card__title">Phone</h3>
                            <a class="contact-stat-card__value" href="tel:{{ $contact->phone ?? '' }}">{{ $contact->phone ?? '' }}</a>
                            @if(!empty($contact->phone2))
                                <a class="contact-stat-card__value" href="tel:{{ $contact->phone2 }}">{{ $contact->phone2 }}</a>
                            @endif
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="contact-stat-card h-100">
                        <span class="contact-stat-card__icon"><i class="flaticon-email"></i></span>
                        <div>
                            <h3 class="contact-stat-card__title">Email</h3>
                            <a class="contact-stat-card__value" href="mailto:{{ $contact->email ?? '' }}">{{ $contact->email ?? '' }}</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="contact-stat-card h-100">
                        <span class="contact-stat-card__icon"><i class="flaticon-location"></i></span>
                        <div>
                            <h3 class="contact-stat-card__title">Location</h3>
                            <p class="contact-stat-card__value mb-0">{{ $contact->address ?? '' }}, Rwanda</p>
                        </div>
                    </article>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-xl-11 col-xxl-10">
                    <div class="lux-section-head text-center text-lg-start mb-4">
                        <p class="lux-section-head__eyebrow mb-2">Send an inquiry</p>
                        <h2 class="lux-section-head__title mb-2">Contact us</h2>
                        <p class="contact-page-lead text-muted mb-0">Fill in your details, choose WhatsApp or email, then send your message in the app that opens. We only save your inquiry after you confirm it was sent.</p>
                    </div>

                    @if(!empty($product))
                        <div class="alert alert-light border mb-4">
                            You are inquiring about <strong>{{ $product->title }}</strong>. This will be included in your message.
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-4 contact-layout-wrap align-items-stretch">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm site-form-card contact-form-card h-100">
                                <div class="card-body p-4 p-lg-5">
                                    @if(! $channelsReady)
                                        <div class="alert alert-warning mb-0">
                                            <strong>Form temporarily unavailable.</strong>
                                            An administrator must configure a valid <strong>email</strong> and <strong>WhatsApp phone number</strong> in site settings before inquiries can be submitted.
                                        </div>
                                    @else
                                        <form action="{{ route('sendMessage') }}" method="POST" class="row g-3 site-channel-form" data-form-type="contact" novalidate>
                                            @csrf
                                            <input type="hidden" name="started_at" value="{{ now()->timestamp }}">
                                            @if(!empty($productReference))
                                                <input type="hidden" name="product_reference" value="{{ $productReference }}">
                                            @endif
                                            <div class="site-hp-field" aria-hidden="true">
                                                <label for="website_contact">Website</label>
                                                <input type="text" name="website" id="website_contact" tabindex="-1" autocomplete="off">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Full name <span class="text-danger">*</span></label>
                                                <input type="text" name="names" class="form-control" required maxlength="255" value="{{ old('names') }}" autocomplete="name">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                                <input type="tel" name="phone" class="form-control" required minlength="10" maxlength="64" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" pattern="[\d\s\+\-\(\)]{10,}">
                                                <small class="text-muted">Active number with at least 10 digits.</small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" required maxlength="255" value="{{ old('email') }}" autocomplete="email">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Organisation <span class="text-muted small">(optional)</span></label>
                                                <input type="text" name="organization" class="form-control" maxlength="255" value="{{ old('organization') }}" placeholder="Company, brand, NGO, school…">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label mb-2">What is this about? <span class="text-muted small">(select any)</span></label>
                                                <div class="row g-2 get-involved-checks">
                                                    @foreach($contactInterestOptions as $val => $label)
                                                        <div class="col-sm-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="interests[]" value="{{ $val }}" id="contact_int_{{ $val }}" {{ in_array($val, $oldInterests, true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="contact_int_{{ $val }}">{{ $label }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                                <textarea name="message" class="form-control" rows="6" required minlength="10" maxlength="20000" placeholder="Tell us about your bag order, partnership idea, timeline, quantities, or question…">{{ old('message') }}</textarea>
                                            </div>
                                            @include('frontend.includes.form-channel-submit', ['formType' => 'contact', 'formSource' => 'contact'])
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="contact-map-block h-100 d-flex flex-column">
                                <div class="lux-section-head mb-3">
                                    <p class="lux-section-head__eyebrow mb-2">Find us</p>
                                    <h2 class="lux-section-head__title mb-0 h4">Masoro factory &amp; community</h2>
                                </div>
                                <div class="tp-location__info-box contact-map-wrap flex-grow-1">
                                    @if($mapIframeHtml !== '')
                                        <div class="contact-map-wrap__frame">
                                            {!! $mapIframeHtml !!}
                                        </div>
                                    @else
                                        <iframe src="{{ $resolvedMapSrc }}" width="100%" height="100%" class="contact-map-wrap__iframe" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Abahizi Rwanda location map"></iframe>
                                    @endif
                                </div>
                                <div class="contact-map-caption">
                                    <span class="contact-map-caption__label">Visit</span>
                                    <span class="contact-map-caption__value">{{ $contact->address ?? 'Masoro, Rulindo District' }} · Rwanda</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
