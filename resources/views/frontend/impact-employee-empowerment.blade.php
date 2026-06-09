@extends('layouts.frontbase')

@section('title', __('site.nav.employee_empowerment'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'impact_employee',
        'title' => __('site.nav.employee_empowerment'),
        'caption' => __('site.impact.empower_lead'),
    ])

    <section class="lux-section impact-detail">
        <div class="container">
            @include('frontend.includes.luxury.artisan-journey')

            @if(($impacts ?? collect())->isNotEmpty())
                <div class="impact-detail__block">
                    <div class="lux-section-head text-center mb-4 mb-lg-5">
                        <p class="lux-section-head__eyebrow">{{ __('site.impact.empower_metrics_eyebrow') }}</p>
                        <h2 class="lux-section-head__title">{{ __('site.impact.empower_metrics_title') }}</h2>
                    </div>
                    <div class="row g-4">
                        @foreach ($impacts as $item)
                            <div class="col-md-6 col-lg-4">
                                <article class="impact-metric-card h-100">
                                    @if(!empty($item->value))
                                        <p class="impact-metric-card__value mb-1">{{ $item->value }}</p>
                                    @endif
                                    <h3 class="impact-metric-card__title">{{ $item->title }}</h3>
                                    @if(!empty($item->description))
                                        <p class="impact-metric-card__desc mb-0">{{ strip_tags($item->description) }}</p>
                                    @endif
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('frontend.includes.bottom')

@endsection
