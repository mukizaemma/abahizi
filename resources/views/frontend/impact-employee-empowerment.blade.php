@extends('layouts.frontbase')

@section('title', __('site.nav.employee_empowerment'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'impact_employee',
        'title' => __('site.nav.employee_empowerment'),
        'caption' => \App\Support\SiteCopy::get('impact_empower_lead'),
    ])

    @include('frontend.includes.impact-pillars', [
        'heading' => 'Our impact pillars',
        'lead' => \App\Support\SiteCopy::get('impact_empower_lead'),
        'tone' => 'cream',
    ])

    @include('frontend.includes.luxury.artisan-journey')

    @include('frontend.includes.bottom')

@endsection
