@extends('layouts.frontbase')

@section('content')

    @include('frontend.includes.landing.hero')
    @include('frontend.includes.landing.about')
    @include('frontend.includes.landing.products')
    @include('frontend.includes.landing.impact')
    @include('frontend.includes.landing.movement')
    @include('frontend.includes.landing.social')
    @include('frontend.includes.landing.contact')

@endsection
