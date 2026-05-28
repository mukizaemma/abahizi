@extends('layouts.frontbase')

@section('title', __('site.manufacturing.title'))

@section('content')

    @include('frontend.includes.page-header', [
        'title' => __('site.manufacturing.title'),
        'caption' => 'CMT handbag manufacturing with lean discipline and artisan excellence in Masoro, Rwanda.',
    ])

    <section class="pt-60 pb-30 grey-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <p class="lux-lead mb-0">
                        {{ strip_tags(html_entity_decode($about->factory_description ?? 'Abahizi Rwanda is a mechanized CMT factory—not a charity workshop. We deliver premium handbags and accessories for global brands with lean operations, rigorous QA, and zero supply-chain compromise on ethics.')) }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.includes.luxury.lean-timeline')
    @include('frontend.includes.luxury.factory-specs-tabs')

    @if($services->isNotEmpty())
        <section class="pt-60 pb-90">
            <div class="container">
                <div class="lux-section-head text-center mb-4">
                    <p class="lux-section-head__eyebrow">Services</p>
                    <h2 class="lux-section-head__title">{{ __('site.nav.expertise') }}</h2>
                </div>
                <div class="row g-4">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <article class="lux-service-card h-100">
                                @if($service->image)
                                    <img src="{{ asset('storage/images/' . $service->image) }}" alt="{{ $service->title }}" class="lux-service-card__img" loading="lazy" decoding="async">
                                @endif
                                <div class="lux-service-card__body">
                                    <h3 class="h5"><a href="{{ route('serviceShow', $service->slug) }}" class="text-dark text-decoration-none">{{ $service->title }}</a></h3>
                                    <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($service->description ?? ''), 100) }}</p>
                                    <a href="{{ route('serviceShow', $service->slug) }}" class="tp-btn btn-sm">Learn more</a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="pb-60">
        @include('frontend.includes.bottom')
    </div>

@endsection
