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
    @include('frontend.includes.luxury.factory-capabilities-banner')
    @include('frontend.includes.luxury.factory-impact')
    @include('frontend.includes.luxury.factory-training')
    @include('frontend.includes.luxury.factory-gallery')
    @include('frontend.includes.luxury.factory-partner-cta')

@endsection
