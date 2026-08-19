@php
    $handbags = $about->handbags_exported ?? '310,000+';
    $since = '2014';
    $brands = ['Kate Spade', 'Coach'];
@endphp

<section class="lh-trust" aria-label="{{ __('site.landing.trust_aria') }}">
    <div class="container">
        <div class="lh-trust__row lh-reveal is-visible">
            <div class="lh-trust__badge">
                <span class="lh-trust__bcorp">B Corp</span>
                <span class="lh-trust__bcorp-label">{{ __('site.landing.trust_bcorp') }}</span>
            </div>

            <div class="lh-trust__facts">
                <p class="lh-trust__fact">
                    <span class="lh-trust__fact-value">{{ $since }}</span>
                    <span class="lh-trust__fact-label">{{ __('site.landing.trust_since') }}</span>
                </p>
                <p class="lh-trust__fact">
                    <span class="lh-trust__fact-value">{{ $handbags }}</span>
                    <span class="lh-trust__fact-label">{{ __('site.landing.trust_handbags') }}</span>
                </p>
                <p class="lh-trust__fact lh-trust__fact--brands">
                    <span class="lh-trust__fact-value">{{ implode(' · ', $brands) }}</span>
                    <span class="lh-trust__fact-label">{{ __('site.landing.trust_brands') }}</span>
                </p>
            </div>
        </div>
    </div>
</section>
