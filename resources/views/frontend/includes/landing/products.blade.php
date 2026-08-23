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

    $infoBySlot = [
        1 => __('site.landing.product_1_info'),
        2 => __('site.landing.product_2_info'),
        3 => __('site.landing.product_3_info'),
    ];

    $showcaseCards = \App\Support\HomeProductShowcase::cards($about ?? null);

    foreach ($showcaseCards as $card) {
        $index = $addLightboxImage($card['src'], $card['title']);
        $gridItems->push([
            'src' => $card['src'],
            'title' => $card['title'],
            'info' => $infoBySlot[$card['slot'] ?? 0] ?? __('site.landing.product_fallback_info'),
            'index' => $index,
        ]);
    }

    if ($gridItems->count() < 3 && isset($homeProducts) && $homeProducts->isNotEmpty()) {
        $usedSrc = $gridItems->pluck('src')->all();
        foreach ($homeProducts as $product) {
            if ($gridItems->count() >= 3) {
                break;
            }
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
                if (in_array($src, $usedSrc, true)) {
                    continue;
                }
                $index = $addLightboxImage($src, $title);
                if ($firstIndex === null) {
                    $firstIndex = $index;
                    $firstSrc = $src;
                }
            }

            if ($firstSrc) {
                $usedSrc[] = $firstSrc;
                $excerpt = trim(strip_tags((string) ($product->excerpt ?? $product->description ?? '')));
                $gridItems->push([
                    'src' => $firstSrc,
                    'title' => $title,
                    'info' => $excerpt !== ''
                        ? \Illuminate\Support\Str::limit($excerpt, 90, '…')
                        : __('site.landing.product_fallback_info'),
                    'index' => $firstIndex,
                    'url' => route('productShow', $product->slug ?? $product->id),
                ]);
            }
        }
    }

    if ($gridItems->count() < 3) {
        $fallbacks = [
            [__('site.landing.product_1_title'), __('site.landing.product_1_info'), asset('assets/img/product/product-1-1.jpg')],
            [__('site.landing.product_2_title'), __('site.landing.product_2_info'), asset('assets/img/product/product-1-2.jpg')],
            [__('site.landing.product_3_title'), __('site.landing.product_3_info'), asset('assets/img/product/product-1-3.jpg')],
        ];
        foreach ($fallbacks as [$title, $info, $src]) {
            if ($gridItems->count() >= 3) {
                break;
            }
            $index = $addLightboxImage($src, $title);
            $gridItems->push([
                'src' => $src,
                'title' => $title,
                'info' => $info,
                'index' => $index,
            ]);
        }
    }

    $craftFeatures = [
        ['icon' => 'fa-gem', 'title' => __('site.landing.craft_1_title'), 'text' => __('site.landing.craft_1_text')],
        ['icon' => 'fa-scissors', 'title' => __('site.landing.craft_2_title'), 'text' => __('site.landing.craft_2_text')],
        ['icon' => 'fa-bag-shopping', 'title' => __('site.landing.craft_3_title'), 'text' => __('site.landing.craft_3_text')],
        ['icon' => 'fa-leaf', 'title' => __('site.landing.craft_4_title'), 'text' => __('site.landing.craft_4_text')],
    ];
@endphp

<section class="lh-craft" id="lh-products" aria-labelledby="lh-products-title">
    <div class="container">
        <div class="lh-craft__grid">
            <div class="lh-craft__copy lh-reveal">
                <p class="lh-eyebrow">{{ __('site.landing.products_title') }}</p>
                <h2 id="lh-products-title" class="lh-craft__title">{{ __('site.landing.products_lead') }}</h2>
                <ul class="lh-craft__features">
                    @foreach($craftFeatures as $feature)
                        <li>
                            <span class="lh-craft__icon" aria-hidden="true"><i class="fas {{ $feature['icon'] }}"></i></span>
                            <div>
                                <h3>{{ $feature['title'] }}</h3>
                                <p>{{ $feature['text'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('ourProducts') }}" class="lh-btn lh-btn--solid">{{ __('site.landing.products_view_more') }}</a>
            </div>

            <div class="lh-craft__cards">
                @foreach($gridItems->take(3) as $index => $card)
                    <article class="lh-product-card lh-reveal" style="transition-delay: {{ $index * 0.08 }}s">
                        <button
                            type="button"
                            class="lh-product-card__media"
                            data-lh-gallery-open
                            data-lh-gallery-index="{{ $card['index'] }}"
                            aria-haspopup="dialog"
                            aria-controls="lh-product-lightbox"
                        >
                            <img src="{{ $card['src'] }}" alt="{{ $card['title'] }}" loading="{{ $index < 2 ? 'eager' : 'lazy' }}" decoding="async">
                        </button>
                        <div class="lh-product-card__body">
                            <h3 class="lh-product-card__title">{{ $card['title'] }}</h3>
                            <p class="lh-product-card__info">{{ $card['info'] }}</p>
                            @if(! empty($card['url']))
                                <a href="{{ $card['url'] }}" class="lh-product-card__link">
                                    {{ __('site.landing.product_view') }} <span aria-hidden="true">→</span>
                                </a>
                            @else
                                <button
                                    type="button"
                                    class="lh-product-card__link"
                                    data-lh-gallery-open
                                    data-lh-gallery-index="{{ $card['index'] }}"
                                >
                                    {{ __('site.landing.product_view') }} <span aria-hidden="true">→</span>
                                </button>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
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
