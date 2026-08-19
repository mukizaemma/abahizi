@php
    $intro = trim(strip_tags(html_entity_decode($about->factory_description ?? '')));
    if ($intro === '') {
        $intro = __('site.factory.intro_default');
    }

    $highlightUrl = '';
    $highlightAlt = __('site.factory.gallery_alt');
    $firstGallery = collect($factoryGallery ?? [])->first();
    if ($firstGallery instanceof \App\Models\FactoryGalleryImage) {
        $highlightUrl = $firstGallery->url();
        if (trim((string) ($firstGallery->caption ?? '')) !== '') {
            $highlightAlt = $firstGallery->caption;
        }
    } elseif ($firstGallery && ! empty($firstGallery->image)) {
        $highlightUrl = str_contains((string) $firstGallery->image, '/')
            ? asset('storage/' . ltrim($firstGallery->image, '/'))
            : asset('storage/images/gallery/' . $firstGallery->image);
    }

    $whatCards = [
        [
            'icon' => 'fa-industry',
            'title' => __('site.factory.what_cards.cmt_title'),
            'desc' => __('site.factory.what_cards.cmt_desc'),
        ],
        [
            'icon' => 'fa-pen-ruler',
            'title' => __('site.factory.what_cards.custom_title'),
            'desc' => __('site.factory.what_cards.custom_desc'),
        ],
        [
            'icon' => 'fa-gem',
            'title' => __('site.factory.what_cards.craft_title'),
            'desc' => __('site.factory.what_cards.craft_desc'),
        ],
    ];
@endphp

<section class="lux-section factory-what" aria-labelledby="factory-what-title">
    <div class="container">
        <div class="row align-items-center g-4 g-xl-5 mb-4 mb-lg-5">
            <div class="{{ $highlightUrl !== '' ? 'col-lg-6' : 'col-lg-10 col-xl-9 mx-auto text-center' }} lux-section-head lux-section-head--solo">
                <h2 id="factory-what-title" class="lux-section-head__title mb-3">{{ __('site.factory.what_title') }}</h2>
                <p class="lux-lead mb-3">{{ $intro }}</p>
                <ul class="factory-what__trust">
                    <li>{{ __('site.factory.trust_location') }}</li>
                    <li>{{ __('site.factory.trust_export') }}</li>
                    <li>{{ __('site.factory.trust_ownership') }}</li>
                </ul>
                <a href="#factory-partner" class="tp-btn tp-btn--lux factory-what__cta">{{ __('site.factory.intro_cta') }} <span aria-hidden="true">→</span></a>
            </div>
            @if($highlightUrl !== '')
                <div class="col-lg-6">
                    <figure class="factory-what__visual mb-0">
                        <img src="{{ $highlightUrl }}" alt="{{ $highlightAlt }}" loading="eager" decoding="async">
                    </figure>
                </div>
            @endif
        </div>

        <div class="row g-4">
            @foreach($whatCards as $i => $card)
                <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <article class="lux-card lux-card--lift h-100">
                        <span class="lux-card__icon" aria-hidden="true"><i class="fas {{ $card['icon'] }}"></i></span>
                        <h3 class="lux-card__title">{{ $card['title'] }}</h3>
                        <p class="lux-card__desc mb-0">{{ $card['desc'] }}</p>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
