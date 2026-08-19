@php
    $showProductsCta = (bool) ($setting->show_products_publicly ?? false)
        && ($setting->show_products_page ?? true);

    $ctaCards = [
        [
            'icon' => 'fa-handshake',
            'title' => __('site.factory.cta_work_title'),
            'desc' => __('site.factory.cta_work_desc'),
            'action' => __('site.factory.cta_work_action'),
            'href' => route('contacts'),
            'featured' => true,
        ],
        [
            'icon' => 'fa-hand-holding-heart',
            'title' => __('site.factory.cta_community_title'),
            'desc' => __('site.factory.cta_community_desc'),
            'action' => __('site.factory.cta_community_action'),
            'href' => route('impactCommunity'),
            'featured' => false,
        ],
    ];

    if ($showProductsCta) {
        $ctaCards[] = [
            'icon' => 'fa-shopping-bag',
            'title' => __('site.factory.cta_products_title'),
            'desc' => __('site.factory.cta_products_desc'),
            'action' => __('site.factory.cta_products_action'),
            'href' => route('ourProducts'),
            'featured' => false,
        ];
    }

    $colClass = count($ctaCards) === 3 ? 'col-md-6 col-lg-4' : 'col-md-6';
@endphp

<section class="lux-section factory-partner" id="factory-partner" aria-labelledby="factory-partner-title">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
            <p class="lux-section-head__eyebrow mb-2">{{ __('site.factory.cta_eyebrow') }}</p>
            <h2 id="factory-partner-title" class="lux-section-head__title mb-3">{{ __('site.factory.cta_title') }}</h2>
            <p class="lux-lead mb-0 mx-auto" style="max-width: 40rem;">{{ __('site.factory.cta_lead') }}</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($ctaCards as $i => $card)
                <div class="{{ $colClass }} wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <a href="{{ $card['href'] }}" class="factory-partner__card{{ $card['featured'] ? ' is-featured' : '' }}">
                        <span class="factory-partner__icon" aria-hidden="true"><i class="fas {{ $card['icon'] }}"></i></span>
                        <h3 class="factory-partner__title">{{ $card['title'] }}</h3>
                        <p class="factory-partner__desc">{{ $card['desc'] }}</p>
                        <span class="factory-partner__action">{{ $card['action'] }} <span aria-hidden="true">→</span></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
