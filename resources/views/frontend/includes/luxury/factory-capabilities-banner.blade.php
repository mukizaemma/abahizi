@php
    use App\Support\FactoryPageContent;
    use App\Support\SectionBackgroundService;

    $ctaAbout = $about ?? \App\Models\Background::firstOrEmpty();
    $bannerBgUrl = SectionBackgroundService::resolve('factory_capabilities_background', $ctaAbout);

    $defaultCards = __('site.factory.capabilities');
    $savedCards = FactoryPageContent::offerCards($ctaAbout->factory_services_subitems ?? null);
    $capabilityCards = [];
    foreach ($savedCards as $card) {
        if (! empty($card['items'])) {
            $capabilityCards[] = [
                'title' => $card['title'] !== '' ? $card['title'] : __('site.manufacturing.specs_title'),
                'items' => $card['items'],
            ];
        }
    }
    if ($capabilityCards === [] && is_array($defaultCards)) {
        $capabilityCards = $defaultCards;
    }
@endphp

@if($capabilityCards !== [])
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
                <a class="tp-btn tp-btn--lux factory-capabilities-banner__btn" href="#factory-partner">
                    {{ __('site.factory.intro_cta') }} <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
