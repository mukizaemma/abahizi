@extends('layouts.frontbase')

@section('title', $page->title ?? __('site.nav.social_impact_reports'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'impact_reports',
        'title' => $page->title ?? __('site.nav.social_impact_reports'),
        'caption' => empty($page->description) ? __('site.impact.reports_lead') : null,
    ])

    <section class="lux-section impact-detail grey-bg">
        <div class="container">
            @if(!empty($page->description))
                <div class="row justify-content-center mb-4 mb-lg-5">
                    <div class="col-lg-10 col-xl-9 text-center">
                        <p class="lux-lead mb-0">{!! nl2br(e($page->description)) !!}</p>
                    </div>
                </div>
            @endif

            @if($reports->isEmpty())
                <div class="impact-hub__empty text-center">
                    <p class="mb-0">{{ __('site.impact.reports_empty') }}</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($reports as $report)
                        <div class="col-md-6 col-lg-4">
                            <article class="impact-report-card h-100">
                                <h3 class="impact-report-card__title">{{ $report->heading }}</h3>
                                @if(!empty($report->description))
                                    <p class="impact-report-card__desc">{{ \Illuminate\Support\Str::limit($report->description, 140, '…') }}</p>
                                @endif
                                <div class="impact-report-card__actions d-flex flex-wrap gap-2 mt-auto">
                                    <a href="{{ route('impactReportShow', ['slug' => $report->slug]) }}" class="tp-btn tp-btn--lux">{{ __('site.home.updates_read') }}</a>
                                    @if(!empty($report->pdf))
                                        <a href="{{ $report->pdfUrl() }}" class="impact-report-card__pdf" download target="_blank" rel="noopener noreferrer">
                                            <i class="far fa-file-pdf" aria-hidden="true"></i> PDF
                                        </a>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('frontend.includes.bottom')

@endsection
