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
    @include('frontend.includes.luxury.factory-gallery')

    @if(($services ?? collect())->isNotEmpty())
        <section class="lux-section factory-services grey-bg">
            <div class="container">
                <div class="lux-section-head lux-section-head--solo text-center mb-4 mb-lg-5">
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
    @include('frontend.includes.luxury.factory-partner-cta')

@endsection
