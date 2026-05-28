@extends('layouts.frontbase')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => __('site.nav.contact'),
        'caption' => 'Tell us what you need — sourcing, bulk orders, technical support, or partnerships.',
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
                <div class="col-12 col-xl-10 col-xxl-9">
                    <div class="lux-section-head text-center text-lg-start mb-3">
                        <p class="lux-section-head__eyebrow mb-2">Partnership + sourcing inquiries</p>
                        <h2 class="lux-section-head__title mb-0">Tell us how you would like to collaborate</h2>
                    </div>
                    <p class="text-muted mb-3 contact-page-lead text-center text-lg-start">
                        Select any areas that fit, then send your inquiry via WhatsApp or email. We only save your details after you send the message.
                    </p>

                    <div class="contact-socials mb-4 justify-content-center justify-content-lg-start">
                        @if(!empty($setting->facebook))
                            <a href="{{ $setting->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($setting->instagram))
                            <a href="{{ $setting->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($setting->youtube))
                            <a href="{{ $setting->youtube }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
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

                    <div class="card border-0 shadow-sm site-form-card contact-form-card w-100 mb-4 mb-lg-5">
                        <div class="card-body p-4 p-lg-5">
                            <form action="{{ route('storePartnershipInquiry') }}" method="POST" class="row g-3 site-partner-form site-channel-form" data-form-type="partnership">
                                @csrf
                                <input type="hidden" name="started_at" value="{{ now()->timestamp }}">
                                <div class="site-hp-field" aria-hidden="true">
                                    <label for="website_contact">Website</label>
                                    <input type="text" name="website" id="website_contact" tabindex="-1" autocomplete="off">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Organisation (optional)</label>
                                    <input type="text" name="organization" class="form-control" value="{{ old('organization') }}" placeholder="Company, brand, NGO, school…">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Full name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" required value="{{ old('full_name') }}" autocomplete="name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}" autocomplete="tel">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}" autocomplete="email">
                                </div>
                                <div class="col-12">
                                    <label class="form-label mb-2">Areas of interest <span class="text-muted small">(select any)</span></label>
                                    <div class="row g-2 get-involved-checks">
                                        @php
                                            $opts = [
                                                'training' => 'Skills development & training',
                                                'equipment' => 'Equipment or materials',
                                                'fundraising' => 'Fundraising or sponsorship',
                                                'volunteering' => 'Volunteering',
                                                'sales_ambassador' => 'Sales & ambassador programmes',
                                                'wholesale' => 'Wholesale / bulk orders',
                                                'corporate' => 'Corporate or institutional partnership',
                                                'other' => 'Other',
                                            ];
                                            $oldInterests = old('interests', []);
                                        @endphp
                                        @foreach($opts as $val => $label)
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="interests[]" value="{{ $val }}" id="contact_int_{{ $val }}" {{ in_array($val, (array) $oldInterests, true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="contact_int_{{ $val }}">{{ $label }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message</label>
                                    <textarea name="message" class="form-control" rows="5" placeholder="Goals, timeline, product type, expected quantities…">{{ old('message') }}</textarea>
                                    <small class="text-muted d-block mt-2">Tip: include goals, timeline, and the type of partnership you need.</small>
                                </div>
                                @include('frontend.includes.form-channel-submit', ['formType' => 'partnership', 'formSource' => 'contact'])
                            </form>
                        </div>
                    </div>

                    <div class="contact-map-block">
                        <div class="lux-section-head mb-3">
                            <p class="lux-section-head__eyebrow mb-2">Find us</p>
                            <h2 class="lux-section-head__title mb-0 h4">Masoro factory &amp; community</h2>
                        </div>
                        <div class="tp-location__info-box contact-map-wrap contact-map-wrap--stacked">
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
    </section>

@endsection
