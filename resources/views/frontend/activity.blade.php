@extends('layouts.frontbase')

@section('title', $activity->title)

@section('content')

@php
    $hasProjectGallery = $images->count() > 0;
    $hasProgramGallery = isset($programGallery) && $programGallery->count() > 0;
    $hasAnyGallery = $hasProjectGallery || $hasProgramGallery;
@endphp

{{-- Header: cover image 80% width --}}
<section class="activity-hero-section pt-50 pb-30">
    <div class="container">
        @if(!empty($activity->image))
            <div class="activity-hero mx-auto">
                <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="activity-hero__img w-100 rounded-3 shadow-sm">
            </div>
        @endif
    </div>
</section>

{{-- Description + optional galleries: 40% / 60% or full width --}}
<section class="activity-main py-4 py-lg-5 bg-white">
    <div class="container">
        <h1 class="activity-page-title h2 mb-4">{{ $activity->title }}</h1>

        <div class="row g-4 g-lg-5 align-items-start">
            @if($hasAnyGallery)
                <div class="col-12 col-lg-5 activity-desc-col">
                    <div class="postbox__text activity-description">{!! $activity->description !!}</div>
                </div>
                <div class="col-12 col-lg-7 activity-gallery-col">
                    @if($hasProgramGallery)
                        @include('frontend.includes.gallery-featured', [
                            'galleryItems' => $programGallery,
                            'heading' => 'Program gallery',
                            'galleryKey' => 'program-' . $activity->id,
                        ])
                    @endif
                    @if($hasProjectGallery)
                        @include('frontend.includes.gallery-featured', [
                            'galleryItems' => $images,
                            'heading' => 'Project gallery',
                            'galleryKey' => 'project-' . $activity->id,
                        ])
                    @endif
                </div>
            @else
                <div class="col-12">
                    <div class="postbox__text activity-description">{!! $activity->description !!}</div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Related projects (same program), 3 columns --}}
@if($relatedActivities->count() > 0)
<section class="related-projects-section py-5 grey-bg">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5">
            <h2 class="h3 mb-2">Related projects</h2>
            @if($activity->program)
                <p class="text-muted mb-0">More work under <strong>{{ $activity->program->title }}</strong></p>
            @endif
        </div>
        <div class="row g-4 justify-content-center">
            @foreach ($relatedActivities as $rel)
                <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                    <article class="related-project-card h-100 d-flex flex-column bg-white rounded-3 overflow-hidden border shadow-sm">
                        <a href="{{ route('project', ['slug' => $rel->slug]) }}" class="related-project-card__thumb d-block">
                            @if(!empty($rel->image))
                                <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->title }}" class="w-100 object-fit-cover" style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 220px;">No image</div>
                            @endif
                        </a>
                        <div class="p-3 p-4 flex-grow-1 d-flex flex-column">
                            <h3 class="h6 mb-2">
                                <a href="{{ route('project', ['slug' => $rel->slug]) }}" class="text-dark text-decoration-none">{{ $rel->title }}</a>
                            </h3>
                            @php
                                $ex = Str::limit(strip_tags(html_entity_decode($rel->description ?? '')), 110, '…');
                            @endphp
                            <p class="text-muted small flex-grow-1 mb-3">{{ $ex }}</p>
                            <a href="{{ route('project', ['slug' => $rel->slug]) }}" class="tp-btn align-self-start mt-auto">View more</a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('frontend.includes.backImage')

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gallery-featured]').forEach(function (wrap) {
        var mainLink = wrap.querySelector('.gallery-featured__main-link');
        var mainImg = wrap.querySelector('[data-gallery-main]');
        var buttons = wrap.querySelectorAll('.gallery-featured__thumb-btn');
        if (!mainImg || buttons.length === 0) return;

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = this.getAttribute('data-full-url');
                if (!url) return;
                mainImg.src = url;
                if (mainLink) mainLink.href = url;
                buttons.forEach(function (b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });
                this.classList.add('is-active');
                this.setAttribute('aria-pressed', 'true');
            });
        });
    });
});
</script>

@endsection
