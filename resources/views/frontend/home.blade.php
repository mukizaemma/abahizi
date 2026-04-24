@extends('layouts.frontbase')

@section('content')

    <!-- slider-area-start -->
    @include('frontend.includes.slides')
    <!-- slider-area-end -->
    
    <!-- about-area-start -->
    <div class="tp-about-4__area tp-about-4__space p-relative fix grey-bg about-home-section">
        <div class="container">
            <div class="row">
                <div class="col-12 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                    <div class="about-home-intro">
                        <h4 class="tp-section-title about-home-title">About Abahizi Rwanda</h4>
                        @php
                            $aboutDescRaw = $about->description ?? '';
                            $aboutDescPlain = strip_tags(html_entity_decode($aboutDescRaw));
                            $words = Str::limit($aboutDescPlain, 400, '...');
                        @endphp
                        <p class="about-home-lead">{{ $words }}</p>
                        <div class="about-home-actions">
                            <a class="tp-btn about-home-cta" href="{{ route('backgroundDetails') }}">
                                View More <span class="about-home-cta-arrow" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about-area-end -->

    <!-- problem-solution-area-start -->
    <section class="home-problem-solution-section grey-bg pb-70">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".15s">
                    <article class="about-home-pillar h-100">
                        <div class="about-home-pillar__head">
                            <span class="about-home-pillar__icon" aria-hidden="true"><i class="fas fa-triangle-exclamation"></i></span>
                            <h4 class="about-home-pillar__title">The Problem We Address</h4>
                        </div>
                        <p class="about-home-pillar__text mb-0">
                            {{ Str::limit(strip_tags(html_entity_decode($about->problem_statement ?? 'Many skilled women in Rwanda face limited access to stable employment, professional development, and social support systems. At the same time, global fashion brands seek ethical, transparent, and high-quality manufacturing partners but struggle to find suppliers who can deliver both excellence and impact.')), 420, '…') }}
                        </p>
                    </article>
                </div>
                <div class="col-lg-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".25s">
                    <article class="about-home-pillar h-100">
                        <div class="about-home-pillar__head">
                            <span class="about-home-pillar__icon" aria-hidden="true"><i class="fas fa-lightbulb-on"></i></span>
                            <h4 class="about-home-pillar__title">Our Solution</h4>
                        </div>
                        <p class="about-home-pillar__text mb-0">
                            {{ Str::limit(strip_tags(html_entity_decode($about->solution_statement ?? 'Abahizi Rwanda bridges this gap by combining world-class handbag manufacturing with a holistic social support system. We provide dignified employment, continuous skills development, and life-changing support programs while delivering premium handcrafted products to global clients.')), 420, '…') }}
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>
    <!-- problem-solution-area-end -->
     
    <!-- service-area-start -->
    @include('frontend.includes.services')
    <!-- service-area-end -->

    @include('frontend.includes.home-mission-core-values')

    
        <!-- event-area-start -->
{{-- @if($event)
<div class="tp-about-4__area tp-about-4__space p-relative fix grey-bg">
<div class="row">
    <div class="col-xl-12">
        <div class="tp-event__section-title text-center">
            <h4 class="tp-section-title">Upcoming Events </h4>
        </div>
    </div>
</div>
    <div class="tp-about-4__shape d-none d-xxl-block">
        <img src="{{asset('storage/images/events') .$event->image}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="offset-xl-6 offset-lg-6 col-xl-6 col-lg-6 wow tpfadeRight" data-wow-duration=".9s"
            data-wow-delay=".5s">
                <div class="tp-about-4__left-side">
                    <div class="tp-about-4__section-title">
                        <h4 class="tp-section-title">About the Event</h4>
                    </div>
                    <div class="tp-about-4__content">
                        <div class="tp-about-4__text">
                            <div class="postbox__text">{!! $event->description !!}</div>
                        </div>
                        <div class="tp-about-4__btn">
                            <a class="tp-btn" href="{{ $event->registerLink }}">Discover More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-xl-12">
        <div class="tp-event__section-title text-center">
            <h4 class="tp-section-title">Not Active Event Yet </h4>
        </div>
    </div>
</div>
@endif --}}

        <!-- event-area-end -->


    <!-- testimonial-area-start -->
    
    {{-- @include('frontend.includes.testimonials') --}}
    <!-- testimonial-area-end -->

    @include('frontend.includes.home-products')

    <!-- cta-area-start -->
    @include('frontend.includes.bottom')
    <!-- cta-area-end -->

@endsection
