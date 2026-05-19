@extends('layouts.frontbase')

@section('title', $page->title ?? 'Impact Reports')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => $page->title ?? 'Impact Reports',
    ])

    <section class="tp-about-4__area tp-about-4__space grey-bg pt-60 pb-90">
        <div class="container">
            @if(!empty($page->description))
                <div class="row justify-content-center mb-50">
                    <div class="col-lg-10">
                        <div class="postbox__text text-center" style="font-size: 18px; line-height: 1.8;">
                            {!! nl2br(e($page->description)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-4 justify-content-center">
                @forelse($reports as $report)
                    <div class="col-lg-6 col-md-8 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                        <article class="tp-blog-2__item h-100 p-4 bg-white rounded-3 shadow-sm border">
                            <h3 class="h4 mb-3">
                                <a href="{{ route('impactReportShow', ['slug' => $report->slug]) }}" class="text-dark text-decoration-none">
                                    {{ $report->heading }}
                                </a>
                            </h3>
                            @if(!empty($report->description))
                                <p class="mb-4 text-muted">{{ $report->description }}</p>
                            @endif
                            <a href="{{ route('impactReportShow', ['slug' => $report->slug]) }}" class="tp-btn">
                                View more <span class="ms-1" aria-hidden="true">→</span>
                            </a>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <p class="mb-0">Annual reports will be published here soon.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
