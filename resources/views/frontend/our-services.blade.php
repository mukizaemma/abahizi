@extends('layouts.frontbase')

@section('title', 'Our Services')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Services',
    ])

    <div class="tp-blog-2__area pt-90 pb-90">
        <div class="container">
            @if($services->isEmpty())
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted" style="font-size: 18px;">Services will appear here once they are added to the database.</p>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                            <div class="tp-blog-2__item">
                                <a href="{{ route('serviceShow', $service->slug) }}">
                                    <div class="tp-blog-2__thumb p-relative">
                                        @if($service->image)
                                            <img src="{{ asset('storage/images/' . $service->image) }}" alt="{{ $service->title }}" style="height: 240px; object-fit: cover; width: 100%;">
                                        @else
                                            <div style="height:240px;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                                <span class="text-muted">{{ $service->title }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="tp-blog-2__content">
                                    <a href="{{ route('serviceShow', $service->slug) }}">
                                        <h4 class="tp-blog-2__title-sm">{{ $service->title }}</h4>
                                    </a>
                                    <p style="font-size: 16px;">{{ Str::limit(strip_tags($service->description ?? ''), 120) }}</p>
                                    <a href="{{ route('serviceShow', $service->slug) }}">
                                        <div class="tp-blog-2__link text-center">
                                            <span>Read More<i class="flaticon-arrow-right"></i></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
