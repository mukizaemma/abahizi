@extends('layouts.frontbase')

@section('title', __('site.nav.factory'))

@section('content')

    @php
        $factoryHeaderImage = !empty($about->factory_services_image)
            ? asset('storage/images/' . $about->factory_services_image)
            : null;
    @endphp

    @include('frontend.includes.page-header', [
        'pageKey' => 'factory',
        'title' => __('site.nav.factory'),
        'caption' => __('site.factory.header_caption'),
        'image' => $factoryHeaderImage,
    ])

    @include('frontend.includes.luxury.factory-what')
    @include('frontend.includes.luxury.lean-timeline')

    @if(($factoryGallery ?? collect())->isNotEmpty())
        <section class="lux-section factory-gallery" aria-labelledby="factory-gallery-title">
            <div class="container">
                <div class="text-center mb-4 mb-lg-5">
                    <p class="lux-section-head__eyebrow mb-2">{{ __('site.factory.gallery_eyebrow') }}</p>
                    <h2 id="factory-gallery-title" class="lux-section-head__title mb-0">{{ __('site.factory.gallery_title') }}</h2>
                </div>

                <div class="row g-3 g-md-4 factory-gallery__grid">
                    @foreach($factoryGallery as $galleryImage)
                        @php
                            $galleryUrl = $galleryImage instanceof \App\Models\FactoryGalleryImage
                                ? \App\Models\FactoryGalleryImage::publicUrl($galleryImage->image)
                                : (str_contains((string) $galleryImage->image, '/')
                                    ? asset('storage/' . ltrim($galleryImage->image, '/'))
                                    : asset('storage/images/gallery/' . $galleryImage->image));
                        @endphp
                        <div class="col-6 col-lg-4">
                            <a href="{{ $galleryUrl }}" class="factory-gallery-card popup-image d-block h-100">
                                <img
                                    src="{{ $galleryUrl }}"
                                    alt="{{ $galleryImage->caption ?? __('site.factory.gallery_alt') }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                                @if(!empty($galleryImage->caption))
                                    <span class="factory-gallery-card__caption">{{ $galleryImage->caption }}</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(($services ?? collect())->isNotEmpty())
        <section class="lux-section factory-services grey-bg">
            <div class="container">
                <div class="lux-section-head text-center mb-4 mb-lg-5">
                    <p class="lux-section-head__eyebrow">{{ __('site.factory.services_eyebrow') }}</p>
                    <h2 class="lux-section-head__title">{{ __('site.nav.expertise') }}</h2>
                </div>
                <div class="row g-4">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <article class="lux-card lux-card--lift lux-card--media h-100">
                                @if($service->image)
                                    <div class="lux-card__media">
                                        <img src="{{ asset('storage/images/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy" decoding="async">
                                    </div>
                                @endif
                                <div class="lux-card__body">
                                    <h3 class="lux-card__title">
                                        <a href="{{ route('serviceShow', $service->slug) }}" class="lux-card__link">{{ $service->title }}</a>
                                    </h3>
                                    <p class="lux-card__desc">{{ \Illuminate\Support\Str::limit(strip_tags($service->description ?? ''), 120) }}</p>
                                    <a href="{{ route('serviceShow', $service->slug) }}" class="lux-card__action">{{ __('site.factory.learn_more') }} <span aria-hidden="true">→</span></a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('frontend.includes.luxury.factory-capabilities-banner')

@endsection
