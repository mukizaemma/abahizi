@php
    use App\Support\SectionBackgroundService;

    $ctaAbout = $about ?? \App\Models\Background::firstOrEmpty();
    $bannerBgUrl = SectionBackgroundService::resolve('factory_capabilities_background', $ctaAbout);

    $capabilityCards = [
        [
            'title' => 'Capacity',
            'items' => [
                '14,000+ units per season (scalable)',
                'Dedicated CMT lines for handbags & accessories',
                'Seasonal planning aligned with global brand calendars',
            ],
        ],
        [
            'title' => 'Technical capabilities',
            'items' => [
                'Industrial cutting, skiving, and stitching equipment',
                'Specialized beading, embroidery, and leather finishing',
                'Custom hardware application and quality control stations',
            ],
        ],
        [
            'title' => 'Worker benefits',
            'items' => [
                'Full health insurance for employees and families',
                'Paid maternity leave, sick days, and vacation',
                'Vocational training, financial literacy, and employee ownership',
            ],
        ],
    ];
@endphp

<section
    class="factory-capabilities-banner factory-capabilities-banner--parallax"
    aria-labelledby="factory-capabilities-heading"
    data-lux-parallax
    style="--factory-cap-parallax-image: url('{{ $bannerBgUrl }}');"
>
    <div class="factory-capabilities-banner__parallax" data-lux-parallax-layer aria-hidden="true"></div>
    <div class="factory-capabilities-banner__bg">
        <div class="container">
            <div class="factory-capabilities-banner__head text-center">
                <p class="factory-capabilities-banner__eyebrow">{{ __('site.factory.specs_eyebrow') }}</p>
                <h2 id="factory-capabilities-heading" class="factory-capabilities-banner__title">{{ __('site.manufacturing.specs_title') }}</h2>
            </div>

            <div class="row g-3 g-lg-4 factory-capabilities-banner__cards">
                @foreach($capabilityCards as $card)
                    <div class="col-md-6 col-lg-4">
                        <article class="factory-cap-card h-100">
                            <h3 class="factory-cap-card__title">{{ $card['title'] }}</h3>
                            <ul class="factory-cap-card__list">
                                @foreach($card['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="factory-capabilities-banner__actions d-flex flex-wrap justify-content-center gap-3">
                <a class="tp-btn tp-btn--lux factory-capabilities-banner__btn" href="{{ route('contacts') }}">
                    {{ __('site.nav.inquiry') }} <span aria-hidden="true">→</span>
                </a>
                <a class="tp-btn factory-capabilities-banner__btn factory-capabilities-banner__btn--ghost" href="{{ route('impactPage') }}">
                    {{ __('site.nav.impact') }} <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>
