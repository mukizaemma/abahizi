@extends('layouts.frontbase')

@section('title', 'Our Products — Made in Rwanda')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Products',
        'caption' => 'Abahizi Manufacturing - bags, accessories and apparel crafted in Rwanda. We produce to order for partners, retailers, and organisations.',
    ])

    @php
        $productsIntroHtml = trim((string) ($about->products_intro ?? ''));
        if ($productsIntroHtml === '') {
            $productsIntroHtml = '<h4>Our Products (Confidential &amp; Custom)</h4><p>We develop a wide range of handbags and accessories tailored to client needs, including totes, crossbody bags, pouches, and embellished pieces.</p><p>To respect client confidentiality, we do not publicly showcase final branded products. Instead, we focus on delivering custom, high-quality solutions aligned with each partner\'s vision.</p>';
        }
    @endphp
    <section class="products-page-intro grey-bg pt-70 pb-40">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="products-page-intro__card">
                        <div class="products-page-intro__badge">Products intro</div>
                        <div class="products-page-intro__content">{!! $productsIntroHtml !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.includes.product-story-section')

    <section class="shop-catalog-section py-5 grey-bg">
        <div class="container">
            @if(!($setting->show_products_publicly ?? false))
                <div class="text-center py-5">
                    <p class="text-muted mb-3" style="font-size: 24px; line-height: 1.55; font-weight: 600;">
                        Product listings are currently private and available only upon request.
                    </p>
                    <a href="{{ route('contacts') }}" class="tp-btn">Contact us</a>
                </div>
            @else
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
                        <div class="col-6 col-lg-3 mb-0 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".05s">
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
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="shop-product-card__img w-100">
                                        @else
                                            <div class="shop-product-card__placeholder d-flex align-items-center justify-content-center text-muted">No image</div>
                                        @endif
                                    </a>
                                </div>
                                <div class="shop-product-card__body">
                                    <h2 class="shop-product-card__title">
                                        <a href="{{ route('productShow', $product->slug) }}" class="shop-product-card__title-link">{{ $product->title }}</a>
                                    </h2>
                                    @php
                                        $waPhone = preg_replace('/\D+/', '', $setting->phone ?? $setting->phone1 ?? '');
                                        $productPageUrl = url(route('productShow', $product->slug));
                                        $waShareText = rawurlencode('Check out '.$product->title.' — '.$productPageUrl);
                                        $waShareUrl = $waPhone ? 'https://wa.me/'.$waPhone.'?text='.$waShareText : 'https://api.whatsapp.com/send?text='.$waShareText;
                                    @endphp
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
                                            <a href="{{ $waShareUrl }}" class="shop-product-card__btn shop-product-card__btn--wa shop-product-card__btn--row" target="_blank" rel="noopener" title="Share on WhatsApp">
                                                <i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                @endif
            @endif
        </div>
    </section>

    @include('frontend.includes.request-order-cta')

@endsection

<style>
    .products-page-intro__card {
        background: #fff;
        border: 1px solid #ebedf0;
        border-radius: 16px;
        padding: 1.4rem 1.4rem 1.15rem;
        box-shadow: 0 18px 44px rgba(18, 33, 52, 0.08);
        position: relative;
        overflow: hidden;
    }
    .products-page-intro__card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--brand-primary, #fad200), #f2be00);
    }
    .products-page-intro__badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(250, 210, 0, 0.14);
        border: 1px solid rgba(250, 210, 0, 0.35);
        color: #6b5600;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-radius: 999px;
        padding: 0.25rem 0.65rem;
        margin-bottom: 0.75rem;
    }
    .products-page-intro__content h1,
    .products-page-intro__content h2,
    .products-page-intro__content h3,
    .products-page-intro__content h4 {
        font-size: clamp(1.4rem, 2vw, 2rem);
        line-height: 1.2;
        margin-bottom: 0.75rem;
        color: #1e2b3a;
        font-weight: 800;
    }
    .products-page-intro__content p,
    .products-page-intro__content li {
        font-size: clamp(1rem, 1.15vw, 1.12rem);
        line-height: 1.72;
        color: #3b4652;
        margin-bottom: 0.65rem;
    }
    .products-page-intro__content p:last-child {
        margin-bottom: 0;
    }
</style>
