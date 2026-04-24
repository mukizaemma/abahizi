@php
    $headerTitle = $title ?? '';
    $headerCaption = $caption ?? ($setting->page_header_caption ?? null);

    $headerImageUrl = null;
    if (!empty($image)) {
        $headerImageUrl = $image;
    } elseif (!empty($setting->page_header_image ?? null)) {
        $headerImageUrl = asset('storage/images' . $setting->page_header_image);
    } elseif (!empty($about->image2 ?? null)) {
        $headerImageUrl = asset('storage/images/' . $about->image2);
    } elseif (!empty($about->image1 ?? null)) {
        $headerImageUrl = asset('storage/images/' . $about->image1);
    } elseif (!empty($about->image ?? null)) {
        $headerImageUrl = asset('storage/images/' . $about->image);
    }
@endphp

<div class="tp-breadcrumb__area p-relative fix tp-breadcrumb-height"
    @if($headerImageUrl) data-background="{{ $headerImageUrl }}" @endif>
    <div class="tp-breadcrumb__shape-1 z-index-5">
        <img src="{{ asset('assets/img/breadcrumb/breadcrumb-shape-1.png') }}" alt="">
    </div>
    <div class="tp-breadcrumb__shape-2 z-index-5">
        <img src="{{ asset('assets/img/breadcrumb/breadcrumb-shape-2.png') }}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tp-breadcrumb__content z-index-5 text-center">
                    <div class="page-header__top">
                        <div class="tp-breadcrumb__list page-header__home">
                            <span><a href="{{ route('home') }}">Home</a></span>
                        </div>
                        <h3 class="tp-breadcrumb__title text-center mb-0">{{ $headerTitle }}</h3>
                    </div>
                    @if(!empty($headerCaption))
                        <p class="text-center mb-0 mt-2">{{ $headerCaption }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
