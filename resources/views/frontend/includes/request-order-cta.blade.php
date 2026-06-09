<section class="request-order-cta py-5 grey-bg border-top" aria-labelledby="request-order-cta-title">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-8">
                <h2 id="request-order-cta-title" class="tp-section-title mb-2">Ready to work with us?</h2>
                <p class="text-muted mb-4">Use our contact page for custom bag orders, partnerships, bulk sourcing, and all other inquiries.</p>
                <a href="{{ route('contacts', !empty($product) ? ['product' => $product->slug] : []) }}" class="tp-btn request-order-cta__btn">
                    {{ __('site.nav.contact') }} <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>
