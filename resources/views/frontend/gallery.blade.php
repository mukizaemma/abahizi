@extends('layouts.frontbase')

@section('title', __('site.nav.gallery'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'gallery',
        'title' => __('site.nav.gallery'),
        'caption' => 'Photos from our factory, products, and community work.',
    ])

    <div class="tp-gallery-3__area pt-120 pb-120">
        <div class="container">
            @if($gallery->isEmpty())
                <p class="text-center text-muted mb-0">Gallery photos will appear here soon.</p>
            @else
                <div class="row">
                    @foreach($gallery as $image)
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".3s">
                            <div class="tp-gallery-3__item p-relative">
                                <img src="{{ $image->url() }}" alt="{{ $image->caption ?: 'Gallery photo' }}">
                                <div class="tp-gallery-3__icon">
                                    <a class="popup-image" href="{{ $image->url() }}"></a>
                                </div>
                            </div>
                            @if($image->caption)
                                <p class="small text-muted mt-2 mb-0">{{ $image->caption }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
