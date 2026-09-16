@extends('layouts.frontbase')

@section('title', 'Our Products — Made in Rwanda')

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'products',
        'title' => 'Our Products',
        'caption' => __('site.products_page.header_caption'),
    ])

    @include('frontend.includes.landing.products', [
        'showCraftCta' => true,
        'craftCtaHref' => route('contacts'),
        'craftCtaLabel' => __('site.products_page.craft_cta'),
    ])

    <section class="lux-section products-page-paths" aria-labelledby="products-paths-title">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
                <p class="lh-eyebrow mb-2">{{ __('site.products_page.paths_eyebrow') }}</p>
                <h2 id="products-paths-title" class="lux-section-head__title mb-3">{{ __('site.products_page.paths_title') }}</h2>
                <p class="lux-lead mb-0 mx-auto" style="max-width: 40rem;">{{ __('site.products_page.paths_lead') }}</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 wow tpfadeUp" data-wow-duration=".85s">
                    <a href="{{ route('contacts') }}" class="factory-partner__card is-featured h-100">
                        <span class="factory-partner__icon" aria-hidden="true"><i class="fas fa-shopping-bag"></i></span>
                        <h3 class="factory-partner__title">{{ __('site.products_page.buy_title') }}</h3>
                        <p class="factory-partner__desc">{{ __('site.products_page.buy_text') }}</p>
                        <span class="factory-partner__action">{{ __('site.products_page.buy_cta') }} <span aria-hidden="true">→</span></span>
                    </a>
                </div>
                <div class="col-md-6 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="0.08s">
                    <a href="{{ route('contacts') }}" class="factory-partner__card h-100">
                        <span class="factory-partner__icon" aria-hidden="true"><i class="fas fa-industry"></i></span>
                        <h3 class="factory-partner__title">{{ __('site.products_page.make_title') }}</h3>
                        <p class="factory-partner__desc">{{ __('site.products_page.make_text') }}</p>
                        <span class="factory-partner__action">{{ __('site.products_page.make_cta') }} <span aria-hidden="true">→</span></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if(($pageGallery ?? collect())->isNotEmpty())
        <section class="products-page-gallery py-5 grey-bg" aria-labelledby="products-gallery-title">
            <div class="container">
                <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
                    <h2 id="products-gallery-title" class="lux-section-head__title mb-2">{{ __('site.products_page.gallery_title') }}</h2>
                    <p class="text-muted mb-0 mx-auto" style="max-width: 40rem;">{{ __('site.products_page.gallery_lead') }}</p>
                </div>
                <div class="row g-3 g-md-4">
                    @foreach($pageGallery as $image)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a class="products-page-gallery__item d-block popup-image" href="{{ $image->url() }}">
                                <img src="{{ $image->url() }}" alt="{{ $image->caption ?: 'Product photo' }}" class="w-100" loading="lazy" decoding="async">
                                @if($image->caption)
                                    <span class="products-page-gallery__caption">{{ $image->caption }}</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($hasCatalogProducts ?? false)
        <section class="shop-catalog-section py-5" aria-labelledby="shop-catalog-title">
            <div class="container">
                <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
                    <h2 id="shop-catalog-title" class="lux-section-head__title mb-2">{{ __('site.products_page.catalog_title') }}</h2>
                    <p class="text-muted mb-0 mx-auto" style="max-width: 40rem;">{{ __('site.products_page.catalog_lead') }}</p>
                </div>

                <form action="{{ route('ourProducts') }}" method="GET" class="shop-catalog-filters card border-0 shadow-sm mb-4 mb-lg-5 p-3 p-md-4 bg-white">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4 col-lg-4">
                            <label class="form-label small text-muted mb-1">Category</label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 col-lg-5">
                            <label class="form-label small text-muted mb-1">Search</label>
                            <input type="search" name="q" class="form-control" placeholder="Search products…" value="{{ request('q') }}" autocomplete="off">
                        </div>
                        <div class="col-md-3 col-lg-3 d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-grow-1">Search</button>
                            <a href="{{ route('ourProducts') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <p class="text-muted mb-2" style="font-size: 18px;">No products match your filters.</p>
                        <a href="{{ route('ourProducts') }}" class="tp-btn">View all products</a>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-6 col-lg-4 mb-0 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".05s">
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
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="shop-product-card__img w-100" loading="lazy" decoding="async">
                                            @else
                                                <div class="shop-product-card__placeholder d-flex align-items-center justify-content-center text-muted">No image</div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="shop-product-card__body">
                                        @if($product->category)
                                            <p class="shop-product-card__category small text-uppercase text-muted mb-1">{{ $product->category->name }}</p>
                                        @endif
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
                                            <div class="shop-product-card__actions shop-product-card__actions--row">
                                                <a href="{{ route('productShow', $product->slug) }}" class="shop-product-card__btn shop-product-card__btn--primary shop-product-card__btn--row">
                                                    <i class="fas fa-arrow-right-long" aria-hidden="true"></i> View details
                                                </a>
                                                <a href="{{ route('productShow', $product->slug) }}#product-order-form" class="shop-product-card__btn shop-product-card__btn--wa shop-product-card__btn--row">
                                                    <i class="fas fa-cart-shopping" aria-hidden="true"></i> Order
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @include('frontend.includes.product-story-section')

@endsection
