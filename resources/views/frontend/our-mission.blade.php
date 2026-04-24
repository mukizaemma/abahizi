@extends('layouts.frontbase')

@section('title', 'Our Mission')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Mission',
    ])

    <div class="tp-about-4__area tp-about-4__space p-relative fix grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-xl-6 col-lg-6 col-md-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                    <div class="tp-blog-2__item h-100 p-4" style="background:#fff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.06);">
                        <div class="tp-about-4__list-icon text-center mb-20">
                            <i class="flaticon-mission" style="font-size:48px;"></i>
                        </div>
                        <h4 class="tp-about-4__title-sm text-center mb-20">Our Mission</h4>
                        <div class="postbox__text" style="font-size: 18px; line-height: 1.7;">{!! $mission->mission ?? '' !!}</div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                    <div class="tp-blog-2__item h-100 p-4" style="background:#fff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.06);">
                        <div class="tp-about-4__list-icon text-center mb-20">
                            <i class="flaticon-vision" style="font-size:48px;"></i>
                        </div>
                        <h4 class="tp-about-4__title-sm text-center mb-20">Our Vision</h4>
                        <div class="postbox__text" style="font-size: 18px; line-height: 1.7;">{!! $mission->vision ?? '' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.includes.core-values-parallax', [
        'coreValuesParallaxTitle' => 'Our Core Values',
    ])

@endsection
