@extends('layouts.frontbase')

@section('title', $product->title)

@section('content')

@php
    $galleryItems = collect();
    if ($product->image) {
        $galleryItems->push((object) ['image' => $product->image]);
    }
    foreach ($product->images as $im) {
        if ($product->image && $im->image === $product->image) {
            continue;
        }
        $galleryItems->push($im);
    }
    $disc = $product->discountPercent();
    $headerImageUrl = \App\Support\PageHeaderService::resolve(
        'products',
        $product->title,
        null,
        null,
        $about ?? null,
        true
    )['image'];
@endphp

<div class="tp-breadcrumb__area p-relative fix tp-breadcrumb-height" data-background="{{ $headerImageUrl }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tp-breadcrumb__content z-index-5 py-4">
                    <div class="tp-breadcrumb__list small">
                        <span><a href="{{ route('home') }}">Home</a></span>
                        <span class="mx-1">/</span>
                        <span><a href="{{ route('ourProducts') }}">Products</a></span>
                        @if($product->category)
                            <span class="mx-1">/</span>
                            <span><a href="{{ route('ourProducts', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></span>
                        @endif
                    </div>
                    <h3 class="tp-breadcrumb__title text-center h4 mt-2">{{ $product->title }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="shop-product-detail py-5 grey-bg">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-7">
                @if($galleryItems->count() > 0)
                    @include('frontend.includes.gallery-featured', [
                        'galleryItems' => $galleryItems,
                        'heading' => '',
                        'galleryKey' => 'product-' . $product->id,
                    ])
                @else
                    <div class="bg-white rounded-3 p-5 text-center text-muted border">No images uploaded yet.</div>
                @endif
            </div>
            <div class="col-lg-5">
                <div class="shop-product-detail__panel bg-white rounded-3 shadow-sm p-4 p-lg-4">
                    @if($product->category)
                        <p class="text-uppercase small text-muted letter-spacing mb-2">{{ $product->category->name }}</p>
                    @endif
                    <h1 class="h3 mb-3">{{ $product->title }}</h1>
                    <div class="d-flex align-items-baseline flex-wrap gap-2 mb-2">
                        <span class="h4 mb-0 fw-bold">RWF {{ number_format((float) $product->price, 0) }}</span>
                        @if($product->compare_at_price && (float) $product->compare_at_price > (float) $product->price)
                            <span class="text-muted text-decoration-line-through">RWF {{ number_format((float) $product->compare_at_price, 0) }}</span>
                        @endif
                        @if($disc)
                            <span class="badge bg-danger">-{{ $disc }}%</span>
                        @endif
                    </div>
                    <p class="small text-muted mb-3">Indicative guide pricing. We produce to order for partners, bulk buyers, and organisations.</p>
                    @if($product->color)
                        <p class="mb-3"><span class="text-muted">Color:</span> <strong>{{ $product->color }}</strong></p>
                    @endif
                    <div class="postbox__text shop-product-detail__desc mb-4">{!! $product->description !!}</div>
                    <a href="#product-order-form" class="btn btn-lg text-dark fw-semibold shop-detail-btn-primary w-100">
                        <i class="fas fa-cart-shopping me-2" aria-hidden="true"></i> Order this product
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product-order-section grey-bg pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                @include('frontend.includes.product-order-form', ['product' => $product, 'setting' => $setting])
            </div>
        </div>
    </div>
</section>

@include('frontend.includes.product-story-section')

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gallery-featured]').forEach(function (wrap) {
        var mainLink = wrap.querySelector('.gallery-featured__main-link');
        var mainImg = wrap.querySelector('[data-gallery-main]');
        var buttons = wrap.querySelectorAll('.gallery-featured__thumb-btn');
        if (!mainImg || buttons.length === 0) return;
        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = this.getAttribute('data-full-url');
                if (!url) return;
                mainImg.src = url;
                if (mainLink) mainLink.href = url;
                buttons.forEach(function (b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });
                this.classList.add('is-active');
                this.setAttribute('aria-pressed', 'true');
            });
        });
    });
});
</script>

@endsection
