@extends('layouts.frontbase')

@section('title', $service->title)

@section('content')

    @include('frontend.includes.page-header', [
        'title' => $service->title,
    ])

    <div class="tp-about-4__area tp-about-4__space p-relative fix grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    @if($service->image)
                        <div class="mb-40 text-center">
                            <img src="{{ asset('storage/images/' . $service->image) }}" alt="{{ $service->title }}" style="max-width:100%;border-radius:12px;">
                        </div>
                    @endif
                    <div class="tp-about-4__text">
                        <div class="postbox__text" style="font-size: 20px; line-height: 1.8;">{!! $service->description ?? '' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
