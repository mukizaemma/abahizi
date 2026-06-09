@php
    $craftImages = collect();

    if (isset($homeProducts) && $homeProducts->isNotEmpty()) {
        foreach ($homeProducts as $product) {
            if (!empty($product->image)) {
                $craftImages->push([
                    'url' => asset('storage/' . $product->image),
                    'alt' => $product->title,
                    'link' => route('productShow', $product->slug),
                    'placeholder' => false,
                ]);
            }
        }
    }

    if ($craftImages->count() < 6 && isset($homeGallery)) {
        foreach ($homeGallery->take(6 - $craftImages->count()) as $img) {
            if (!empty($img->image)) {
                $craftImages->push([
                    'url' => asset('storage/images/gallery/' . $img->image),
                    'alt' => $img->caption ?? 'Abahizi Rwanda craftsmanship',
                    'link' => route('ourFactory'),
                    'placeholder' => false,
                ]);
            }
        }
    }

    if ($craftImages->count() < 6 && !empty($about->factory_services_image ?? null)) {
        $craftImages->push([
            'url' => asset('storage/images/' . $about->factory_services_image),
            'alt' => 'Abahizi Rwanda factory',
            'link' => route('ourFactory'),
            'placeholder' => false,
        ]);
    }

    $fallbackAssets = [
        asset('assets/img/slider/slider-3-1.jpg'),
        asset('assets/img/breadcrumb/breadcrumb-bg-1.jpg'),
    ];

    while ($craftImages->count() < 6) {
        $index = $craftImages->count();
        $craftImages->push([
            'url' => $fallbackAssets[$index % count($fallbackAssets)],
            'alt' => __('site.home.craft_title'),
            'link' => route('ourFactory'),
        ]);
    }
@endphp

<section class="lux-section home-craft-showcase" aria-labelledby="home-craft-showcase-title">
    <div class="container">
        <div class="row align-items-end justify-content-between g-3 mb-4 mb-lg-5">
            <div class="col-lg-7">
                <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.craft_eyebrow') }}</p>
                <h2 id="home-craft-showcase-title" class="lux-section-head__title mb-2">{{ __('site.home.craft_title') }}</h2>
                <p class="home-craft-showcase__lead mb-0">{{ __('site.home.craft_lead') }}</p>
            </div>
            <div class="col-lg-auto text-lg-end">
                <a href="{{ route('ourFactory') }}" class="tp-btn tp-btn--outline-dark">{{ __('site.nav.factory') }} <span aria-hidden="true">→</span></a>
            </div>
        </div>

        <div class="home-craft-showcase__grid">
            @foreach($craftImages->take(6) as $i => $item)
                <a
                    href="{{ $item['link'] }}"
                    class="home-craft-showcase__cell home-craft-showcase__cell--{{ ($i % 6) + 1 }} wow tpfadeUp"
                    data-wow-duration=".9s"
                    data-wow-delay="{{ number_format(($i % 3) * 0.1, 1) }}s"
                >
                    <img src="{{ $item['url'] }}" alt="{{ $item['alt'] }}" loading="lazy" decoding="async">
                    <span class="home-craft-showcase__shade" aria-hidden="true"></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
