@php
    $intro = trim(strip_tags(html_entity_decode($about->factory_description ?? '')));
    if ($intro === '') {
        $intro = __('site.factory.intro_default');
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
        <div class="row justify-content-center mb-4 mb-lg-5">
            <div class="col-lg-10 col-xl-9 text-center">
                <p class="lux-section-head__eyebrow mb-2">{{ __('site.factory.what_eyebrow') }}</p>
                <h2 id="factory-what-title" class="lux-section-head__title mb-3">{{ __('site.factory.what_title') }}</h2>
                <p class="lux-lead mb-0">{{ $intro }}</p>
            </div>
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
