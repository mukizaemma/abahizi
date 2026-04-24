@extends('layouts.frontbase')

@section('title', 'Our Impact')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Impact',
    ])

    <div class="tp-blog-2__area pt-90 pb-90">
        <div class="container">
            @if($impacts->isEmpty())
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted" style="font-size: 18px;">Impact stories will appear here when they are published.</p>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach ($impacts as $item)
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                            <div class="tp-blog-2__item">
                                <div class="tp-blog-2__thumb p-relative">
                                    @if($item->image)
                                        <img src="{{ asset('storage/images/impacts/' . $item->image) }}" alt="{{ $item->title }}" style="height: 240px; object-fit: cover; width: 100%;">
                                    @else
                                        <div style="height:240px;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                            <span class="text-muted">{{ $item->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="tp-blog-2__content">
                                    <h4 class="tp-blog-2__title-sm">{{ $item->title }}</h4>
                                    <p style="font-size: 16px;">{{ Str::limit(strip_tags($item->description ?? ''), 120) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
