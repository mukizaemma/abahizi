@php
    $lightboxItems = collect();
    $gridItems = collect();
    $seen = [];

    $addLightboxImage = function (string $src, string $title) use (&$lightboxItems, &$seen): ?int {
        if ($src === '' || isset($seen[$src])) {
            return isset($seen[$src]) ? $seen[$src] : null;
        }

        $index = $lightboxItems->count();
        $seen[$src] = $index;
        $lightboxItems->push([
            'src' => $src,
            'title' => $title,
        ]);

        return $index;
    };

    $showcaseCards = \App\Support\HomeProductShowcase::cards($about ?? null);

    foreach ($showcaseCards as $card) {
        $index = $addLightboxImage($card['src'], $card['title']);
        $gridItems->push([
            'src' => $card['src'],
            'title' => $card['title'],
            'index' => $index,
        ]);
    }

    if ($gridItems->isEmpty() && isset($homeProducts) && $homeProducts->isNotEmpty()) {
        foreach ($homeProducts as $product) {
            $title = trim((string) $product->title);
            $paths = collect();
            if (! empty($product->image)) {
                $paths->push($product->image);
            }
            foreach ($product->images ?? [] as $image) {
                if (! empty($image->image)) {
                    $paths->push($image->image);
                }
            }

            $firstIndex = null;
            $firstSrc = null;
            foreach ($paths->unique() as $path) {
                $src = asset('storage/' . ltrim($path, '/'));
                $index = $addLightboxImage($src, $title);
                if ($firstIndex === null) {
                    $firstIndex = $index;
                    $firstSrc = $src;
                }
            }

            if ($firstSrc && $gridItems->count() < 3) {
                $gridItems->push([
                    'src' => $firstSrc,
                    'title' => $title,
                    'index' => $firstIndex,
                ]);
            }
        }
    }

    if ($gridItems->isEmpty()) {
        $fallbacks = [
            [__('site.landing.product_1_title'), asset('assets/img/product/product-1-1.jpg')],
            [__('site.landing.product_2_title'), asset('assets/img/product/product-1-2.jpg')],
            [__('site.landing.product_3_title'), asset('assets/img/product/product-1-3.jpg')],
        ];
        foreach ($fallbacks as [$title, $src]) {
            $index = $addLightboxImage($src, $title);
            $gridItems->push([
                'src' => $src,
                'title' => $title,
                'index' => $index,
            ]);
        }
    }
@endphp

<section class="lh-products" id="lh-products" aria-labelledby="lh-products-title">
    <div class="container">
        <div class="lh-products__head lh-reveal">
            <div>
                <h2 id="lh-products-title" class="lh-products__title">{{ __('site.landing.products_lead') }}</h2>
            </div>
        </div>

        <div class="lh-products__grid" data-count="{{ $gridItems->count() }}">
            @foreach($gridItems as $index => $card)
                <button
                    type="button"
                    class="lh-product-card lh-reveal"
                    style="transition-delay: {{ $index * 0.08 }}s"
                    data-lh-gallery-open
                    data-lh-gallery-index="{{ $card['index'] }}"
                    aria-haspopup="dialog"
                    aria-controls="lh-product-lightbox"
                >
                    <span class="lh-product-card__media">
                        <img src="{{ $card['src'] }}" alt="{{ $card['title'] }}" loading="{{ $index < 2 ? 'eager' : 'lazy' }}" decoding="async">
                        <span class="lh-product-card__shade" aria-hidden="true"></span>
                        <span class="lh-product-card__body">
                            <span class="lh-product-card__title">{{ $card['title'] }}</span>
                        </span>
                    </span>
                </button>
            @endforeach
        </div>

        <div class="lh-products__more lh-reveal">
            <a href="{{ route('ourProducts') }}" class="lh-btn lh-btn--ghost-dark">{{ __('site.landing.products_view_more') }}</a>
        </div>
    </div>
</section>

<div
    class="lh-lightbox"
    id="lh-product-lightbox"
    hidden
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="lh-product-lightbox-title"
>
    <div class="lh-lightbox__backdrop" data-lh-gallery-close></div>
    <div class="lh-lightbox__dialog">
        <button type="button" class="lh-lightbox__close" data-lh-gallery-close aria-label="{{ __('site.landing.gallery_close') }}">
            <i class="fal fa-times" aria-hidden="true"></i>
        </button>
        <button type="button" class="lh-lightbox__nav lh-lightbox__nav--prev" data-lh-gallery-prev aria-label="{{ __('site.landing.gallery_prev') }}">
            <i class="fal fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button type="button" class="lh-lightbox__nav lh-lightbox__nav--next" data-lh-gallery-next aria-label="{{ __('site.landing.gallery_next') }}">
            <i class="fal fa-chevron-right" aria-hidden="true"></i>
        </button>
        <figure class="lh-lightbox__figure">
            <img src="" alt="" data-lh-gallery-image>
            <figcaption>
                <span id="lh-product-lightbox-title" data-lh-gallery-title></span>
                <span class="lh-lightbox__count" data-lh-gallery-count></span>
            </figcaption>
        </figure>
    </div>
</div>

<script type="application/json" id="lh-product-gallery-data">@json($lightboxItems->values())</script>
