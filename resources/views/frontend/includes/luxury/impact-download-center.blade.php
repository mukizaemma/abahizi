@php
    $downloadReports = ($navImpactReports ?? collect())->filter(fn ($r) => ! empty($r->pdf));
@endphp

<section class="lux-downloads" aria-labelledby="lux-downloads-heading">
    <div class="container">
        <div class="lux-section-head lux-section-head--solo text-center">
            <h2 id="lux-downloads-heading" class="lux-section-head__title">{{ __('site.impact.downloads_title') }}</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($downloadReports as $report)
                <div class="col-md-6 col-lg-5">
                    <article class="lux-downloads__card">
                        <h3 class="lux-downloads__title">{{ $report->heading }}</h3>
                        @if(!empty($report->description))
                            <p class="lux-downloads__desc">{{ \Illuminate\Support\Str::limit($report->description, 120, '…') }}</p>
                        @endif
                        <a href="{{ $report->pdfUrl() }}" class="tp-btn lux-downloads__btn" download target="_blank" rel="noopener noreferrer">
                            <i class="far fa-file-pdf" aria-hidden="true"></i>
                            {{ $report->pdfButtonLabel() }}
                        </a>
                    </article>
                </div>
            @empty
                <div class="col-md-6 col-lg-5">
                    <article class="lux-downloads__card">
                        <h3 class="lux-downloads__title">Social Impact Report</h3>
                        <p class="lux-downloads__desc">Comprehensive outcomes across employment, training, and community programs.</p>
                        <a href="{{ route('impactReports') }}" class="tp-btn lux-downloads__btn">View impact reports</a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-5">
                    <article class="lux-downloads__card">
                        <h3 class="lux-downloads__title">Social Enterprise Model</h3>
                        <p class="lux-downloads__desc">How employee ownership and ethical manufacturing create shared value.</p>
                        <a href="{{ route('ourModel') }}" class="tp-btn lux-downloads__btn">Our model</a>
                    </article>
                </div>
            @endforelse
        </div>
    </div>
</section>
