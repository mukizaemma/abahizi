@extends('layouts.frontbase')

@section('title', $report->heading)

@section('content')

    @include('frontend.includes.page-header', [
        'title' => $report->heading,
    ])

    <section class="impact-report-pdf-viewer pt-40 pb-90">
        <div class="container">
            <div class="row justify-content-center mb-30">
                <div class="col-lg-10 d-flex flex-wrap gap-3 justify-content-between align-items-center">
                    <a href="{{ route('impactReports') }}" class="tp-btn tp-btn-sm theme-2-bg">&larr; Back to Impact Reports</a>
                    <a href="{{ $report->pdfUrl() }}" class="tp-btn tp-btn-sm" target="_blank" rel="noopener noreferrer" download>
                        Download PDF
                    </a>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="impact-report-pdf-viewer__frame bg-white rounded-3 shadow-sm border overflow-hidden">
                        <iframe
                            src="{{ $report->pdfUrl() }}#toolbar=1"
                            title="{{ $report->heading }}"
                            class="w-100"
                            style="min-height: 80vh; border: 0;"
                            loading="lazy"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
