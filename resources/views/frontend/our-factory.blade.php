@extends('layouts.frontbase')

@section('title', 'Our Factory')

@section('content')

    @php
        $factoryHeaderImage = !empty($about->factory_services_image)
            ? asset('storage/images/' . $about->factory_services_image)
            : null;
        $factoryDescription = trim((string) ($about->factory_description ?? ''));
    @endphp

    @include('frontend.includes.page-header', [
        'title' => 'Our Factory',
        'image' => $factoryHeaderImage,
    ])

    <section class="page-standalone grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-xl-10 col-xxl-9">
                    <div class="postbox__text">
                        <p class="mb-0 fw-bold" style="font-size: 1.2rem; line-height: 1.8; color: #2c2c2c;">
                            {!! $factoryDescription !== '' ? $factoryDescription : 'Our production space brings together tailoring workshops, quality craftsmanship, and hands-on learning where trainees and staff create products that carry our mission forward.' !!}
                        </p>
                    </div>
                </div>
            </div>

            @if(($factoryGallery ?? collect())->count() > 0)
                <div class="row g-4">
                    @foreach($factoryGallery as $galleryImage)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ asset('storage/images/gallery/' . $galleryImage->image) }}" class="factory-gallery-card popup-image d-block">
                                <img src="{{ asset('storage/images/gallery/' . $galleryImage->image) }}" alt="{{ $galleryImage->caption ?? 'Factory gallery image' }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <style>
        .factory-gallery-card {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(44, 44, 44, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #f5f5f5;
        }

        .factory-gallery-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12);
        }

        .factory-gallery-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
    </style>

@endsection
