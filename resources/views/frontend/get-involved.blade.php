@extends('layouts.frontbase')

@section('title', 'Get involved')

@section('content')

@include('frontend.includes.page-header', [
    'title' => 'Get involved',
    'caption' => 'Partner with Abahizi Manufacturing - a social enterprise combining skills development, capacity building, and revenue generation for communities across Rwanda.',
])

<section class="py-5 grey-bg">
    <div class="container">
        @php
            $waNumber = preg_replace('/\D+/', '', $setting->phone ?? $setting->phone1 ?? '');
        @endphp
        <div class="row g-4 mb-4 mb-lg-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-3">Skills &amp; training</h4>
                        <p class="text-muted mb-0 small">Co-design workshops, artisan training, and workplace learning that strengthen livelihoods.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-3">Equipment &amp; resources</h4>
                        <p class="text-muted mb-0 small">Donate tools, materials, or infrastructure that help scale ethical production.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-3">Fundraising &amp; sponsorship</h4>
                        <p class="text-muted mb-0 small">Support programmes that combine impact with sustainable business growth.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-3">Volunteering</h4>
                        <p class="text-muted mb-0 small">Share time and expertise in mentoring, logistics, design, or communications.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-3">Sales &amp; ambassadors</h4>
                        <p class="text-muted mb-0 small">Represent Made in Rwanda products in your network, store, or institution.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-3">Wholesale &amp; corporate</h4>
                        <p class="text-muted mb-0 small">Bulk orders, corporate gifting, and long-term supply partnerships welcome.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-4 mb-lg-5">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm request-order-cta__card">
                    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                        <p class="mb-0 text-center text-md-start">Reach us quickly through email or WhatsApp for faster collaboration support.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @if(!empty($setting->email))
                                <a href="mailto:{{ $setting->email }}" class="btn btn-outline-secondary">
                                    <i class="far fa-envelope me-1"></i> {{ $setting->email }}
                                </a>
                            @endif
                            @if($waNumber !== '')
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer" class="btn btn-success" aria-label="Chat on WhatsApp">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
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

                <div class="card border-0 shadow-sm site-form-card">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 mb-3">Tell us how you would like to collaborate</h2>
                        <p class="text-muted mb-4">Select any areas that fit. We will reply by email or phone to explore next steps.</p>

                        <form action="{{ route('storePartnershipInquiry') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Organisation (optional)</label>
                                <input type="text" name="organization" class="form-control" value="{{ old('organization') }}" placeholder="Company, NGO, school, church…">
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
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="interests[]" value="{{ $val }}" id="int_{{ $val }}" {{ in_array($val, (array) $oldInterests, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="int_{{ $val }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Goals, timeline, how you heard about us…">{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg fw-semibold text-dark site-form-submit">Send inquiry</button>
                                <a href="{{ route('contacts') }}" class="btn btn-outline-secondary btn-lg ms-0 ms-md-2 mt-2 mt-md-0">General contact</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
