@extends('layouts.frontbase')

@section('title', 'Our Programs')

@section('content')


        @include('frontend.includes.page-header', [
            'title' => 'Our Programs',
        ])

    <!-- service-area-start -->

    
    <div class="tp-blog-2__area tp-blog-2__space programs-home-grid pb-90">
        <div class="container">
            <div class="row g-4">
                @forelse ($programs as $rs)
                <div class="col-lg-6 mb-0 wow tpfadeUp" data-wow-duration=".9s"
                data-wow-delay=".3s">
                    <article class="program-list-card h-100 d-flex flex-column bg-white rounded-3 overflow-hidden border shadow-sm">
                        <a href="{{ route('project', ['slug' => $rs->slug]) }}" class="program-list-card__thumb d-block position-relative">
                            <img src="{{ asset('storage/' . $rs->image) }}" alt="{{ $rs->title }}" class="w-100 program-list-card__img">
                        </a>
                        <div class="program-list-card__body p-4 d-flex flex-column flex-grow-1">
                            <h2 class="h5 mb-3">
                                <a href="{{ route('project', ['slug' => $rs->slug]) }}" class="text-dark text-decoration-none">{{ $rs->title }}</a>
                            </h2>
                            <div class="tp-about-3__text flex-grow-1">
                                @php
                                $descPlain = strip_tags(html_entity_decode($rs->description ?? ''));
                                $words = Str::limit($descPlain, 140, '…');
                                @endphp

                                <p class="program-list-card__excerpt mb-4">{{ $words }}</p>

                                <a href="{{ route('project', ['slug' => $rs->slug]) }}" class="tp-btn program-list-card__cta align-self-start mt-auto">
                                    View more <span class="ms-1" aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted mb-0" style="font-size: 18px;">Programs will appear here when they are published.</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>
    <!-- service-area-end -->

    @include('frontend.includes.programs-dual-cta')

        <!-- cta-area-start -->
            @include('frontend.includes.backImage')
        <!-- cta-area-end -->


@endsection
