@extends('layouts.frontbase')

@section('title', $report->heading)

@section('content')

    <section class="impact-report-hero pt-120 pb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9 text-center">
                    <p class="impact-report-hero__back mb-4">
                        <a href="{{ route('impactReports') }}" class="text-muted text-decoration-none">&larr; All impact reports</a>
                    </p>
                    @if(!empty($report->highlight_title))
                        <h1 class="impact-report-hero__title">{{ $report->highlight_title }}</h1>
                    @else
                        <h1 class="impact-report-hero__title">{{ $report->heading }}</h1>
                    @endif
                    @if(!empty($report->highlight_message))
                        <div class="impact-report-hero__message postbox__text">
                            {!! $report->highlight_message !!}
                        </div>
                    @elseif(!empty($report->description))
                        <p class="impact-report-hero__message">{{ $report->description }}</p>
                    @endif
                    @if($report->pdfUrl())
                        <a
                            href="{{ $report->pdfUrl() }}"
                            class="tp-btn impact-report-hero__pdf-btn mt-4"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ $report->pdfButtonLabel() }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($galleryImages->isNotEmpty())
        <section class="impact-report-gallery pb-90 grey-bg">
            <div class="container">
                <div class="row justify-content-center mb-40">
                    <div class="col-lg-8 text-center">
                        <h2 class="tp-section-title mb-2">Highlights from the year</h2>
                        <p class="text-muted mb-0">Key moments and experiences from this reporting period.</p>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($galleryImages as $image)
                        <div class="col-sm-6 col-lg-4 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".05s">
                            <figure class="impact-report-gallery__item h-100">
                                <img
                                    src="{{ $image->imageUrl() }}"
                                    alt="{{ $image->caption ?? $report->heading }}"
                                    class="impact-report-gallery__img w-100"
                                    loading="lazy"
                                >
                                @if(!empty($image->caption))
                                    <figcaption class="impact-report-gallery__caption">{{ $image->caption }}</figcaption>
                                @endif
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
