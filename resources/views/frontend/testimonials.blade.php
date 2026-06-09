@extends('layouts.frontbase')

@section('title', 'Home Page')

@section('content')


        @include('frontend.includes.page-header', [
            'pageKey' => 'testimonials',
            'title' => 'Our Testimonials',
        ])

    <!-- testimonial-area-start -->
    @include('frontend.includes.testimonials')
    <!-- testimonial-area-end -->

        <!-- cta-area-start -->
            @include('frontend.includes.backImage')
        <!-- cta-area-end -->


@endsection
