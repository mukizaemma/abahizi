@extends('layouts.frontbase')

@section('title', 'Home Page')

@section('content')


        @include('frontend.includes.page-header', [
            'title' => $program->title,
        ])

    <section class="tp-blog-2__area tp-blog-2__space programs-home-grid pb-90">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="tp-blog-2__section-title pb-50 text-center">
                        <h4 class="tp-section-title">Initiatives under this program</h4>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($program->activities as $initiative)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <article class="program-list-card h-100 d-flex flex-column bg-white rounded-3 overflow-hidden border shadow-sm">
                            <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="program-list-card__thumb d-block position-relative">
                                @if(!empty($initiative->image))
                                    <img src="{{ asset('storage/' . $initiative->image) }}" alt="{{ $initiative->title }}" class="w-100 program-list-card__img">
                                @else
                                    <div class="w-100 d-flex align-items-center justify-content-center program-list-card__img text-muted" style="background:#efefef;">
                                        No image
                                    </div>
                                @endif
                            </a>
                            <div class="program-list-card__body p-4 d-flex flex-column flex-grow-1">
                                <h2 class="h5 mb-3">
                                    <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="text-dark text-decoration-none">{{ $initiative->title }}</a>
                                </h2>
                                @php
                                    $descPlain = strip_tags(html_entity_decode($initiative->description ?? ''));
                                @endphp
                                <p class="program-list-card__excerpt mb-4">{{ \Illuminate\Support\Str::limit($descPlain, 140, '…') }}</p>
                                <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="tp-btn program-list-card__cta align-self-start mt-auto">
                                    View initiative <span class="ms-1" aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted mb-0">No initiatives have been added to this program yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

        <!-- cta-area-start -->
            @include('frontend.includes.backImage')
        <!-- cta-area-end -->


@endsection
