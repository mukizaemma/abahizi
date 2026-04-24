@php
    $waNumber = preg_replace('/\D+/', '', $setting->phone ?? $setting->phone1 ?? '');
@endphp
<section class="request-order-cta py-5 grey-bg border-top" aria-labelledby="request-order-cta-title">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-xl-8">
                <h2 id="request-order-cta-title" class="tp-section-title mb-2">How to get involved</h2>
                <p class="text-muted mb-0">Choose a collaboration path below, then continue to the Get involved page to submit the form and connect with our communications team directly.</p>
            </div>
        </div>
        <div class="row g-3 justify-content-center mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-2">Partnerships</h4>
                        <p class="text-muted mb-0 small">Collaborate with us through strategic, social, or institutional partnerships.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-2">Skills &amp; Capacity</h4>
                        <p class="text-muted mb-0 small">Support artisan development through training, coaching, or technical assistance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 get-involved-highlight">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-2">Funding &amp; Sponsorship</h4>
                        <p class="text-muted mb-0 small">Invest in programs that strengthen livelihoods and expand ethical manufacturing impact.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm request-order-cta__card">
                    <div class="card-body p-4 p-lg-5 text-center text-md-start">
                        <div class="row align-items-center g-4">
                            <div class="col-md">
                                <h3 class="h4 mb-2">Continue to Get involved</h3>
                                <p class="text-muted mb-0 mb-md-0">Open the Get involved page for the structured options form, or contact our communications team directly by email or WhatsApp.</p>
                            </div>
                            <div class="col-md-auto d-flex flex-column flex-md-row gap-2">
                                <a href="{{ route('getInvolved') }}" class="btn btn-lg fw-semibold text-dark request-order-cta__btn">
                                    <i class="fas fa-hands-helping me-2"></i> Go to Get involved form
                                </a>
                                @if(!empty($setting->email))
                                    <a href="mailto:{{ $setting->email }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="far fa-envelope me-1"></i> Email communications
                                    </a>
                                @endif
                                @if($waNumber !== '')
                                    <a href="https://wa.me/{{ $waNumber }}" class="btn btn-success btn-lg" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
