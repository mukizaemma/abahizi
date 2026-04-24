@php
    $productsEnabled = (bool) ($setting->show_products_publicly ?? false);
    $productsIntro = trim((string) ($about->products_intro ?? ''));
@endphp
@if($productsEnabled && (isset($homeProducts) && $homeProducts->isNotEmpty()))
<div class="home-products-teaser tp-blog-2__area pt-120 pb-70 grey-bg">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-blog-2__section-title pb-45 text-center">
                    <h4 class="tp-section-title">Made in Rwanda — our products</h4>
                    @if($productsIntro !== '')
                        <div class="text-muted mb-0 mx-auto" style="max-width: 52rem;">{!! $productsIntro !!}</div>
                    @else
                        <p class="text-muted mb-0 mx-auto" style="max-width: 36rem;">A glimpse of what we craft. Full details, specifications, and how to order are on each product page.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach ($homeProducts as $product)
                <div class="col-xl-4 col-lg-4 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                    <article class="shop-product-card h-100">
                        <div class="shop-product-card__media position-relative">
                            @php $disc = $product->discountPercent(); @endphp
                            @if($disc)
                                <span class="shop-product-card__badge shop-product-card__badge--sale">-{{ $disc }}%</span>
                            @endif
                            @if($product->is_new)
                                <span class="shop-product-card__badge shop-product-card__badge--new">New</span>
                            @endif
                            <a href="{{ route('productShow', $product->slug) }}" class="d-block shop-product-card__img-wrap">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="shop-product-card__img w-100" loading="lazy">
                                @else
                                    <div class="shop-product-card__placeholder d-flex align-items-center justify-content-center text-muted">No image</div>
                                @endif
                            </a>
                        </div>
                        <div class="shop-product-card__body">
                            <h2 class="shop-product-card__title">
                                <a href="{{ route('productShow', $product->slug) }}" class="shop-product-card__title-link">{{ $product->title }}</a>
                            </h2>
                            <div class="shop-product-card__price-actions-row">
                                <div class="shop-product-card__price-cluster">
                                    <div class="shop-product-card__price-block shop-product-card__price-block--row">
                                        <div class="shop-product-card__price-row">
                                            <span class="shop-product-card__currency">RWF</span>
                                            <span class="shop-product-card__amount">{{ number_format((float) $product->price, 0) }}</span>
                                        </div>
                                        @if($product->compare_at_price && (float) $product->compare_at_price > (float) $product->price)
                                            <span class="shop-product-card__compare">RWF {{ number_format((float) $product->compare_at_price, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('productShow', $product->slug) }}" class="shop-product-card__btn shop-product-card__btn--primary shop-product-card__btn--row">
                                    <i class="fas fa-arrow-right-long" aria-hidden="true"></i> View details
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12 text-center pt-10">
                <a href="{{ route('ourProducts') }}" class="tp-btn">View more products</a>
            </div>
        </div>
    </div>
</div>
@endif
