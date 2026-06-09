@extends('layouts.frontbase')

@section('title', __('site.impact.title'))

@section('content')

    @php
        $activeTab = in_array(request('tab'), ['empower', 'improve', 'reports'], true)
            ? request('tab')
            : 'empower';
        $impactReports = ($navImpactReports ?? collect())->take(6);
    @endphp

    @include('frontend.includes.page-header', [
        'title' => __('site.impact.title'),
        'caption' => __('site.impact.caption'),
        'compact' => true,
        'hideShapes' => true,
        'imageTop' => true,
    ])

    @include('frontend.includes.luxury.impact-ticker')

    <section class="impact-hub pt-70 pb-90" x-data="{ tab: '{{ $activeTab }}' }">
        <div class="container">
            <div class="impact-hub__intro text-center mb-4 mb-lg-5">
                <p class="lux-section-head__eyebrow mb-2">{{ __('site.impact.hub_eyebrow') }}</p>
                <h2 class="impact-hub__intro-title">{{ __('site.impact.hub_title') }}</h2>
                <p class="impact-hub__intro-lead mb-0">{{ __('site.impact.hub_lead') }}</p>
            </div>

            <div class="impact-hub__tabs" role="tablist" aria-label="{{ __('site.impact.title') }}">
                <button type="button"
                        class="impact-hub__tab"
                        :class="{ 'is-active': tab === 'empower' }"
                        @click="tab = 'empower'"
                        role="tab"
                        :aria-selected="tab === 'empower'">
                    {{ __('site.nav.employee_empowerment') }}
                </button>
                <button type="button"
                        class="impact-hub__tab"
                        :class="{ 'is-active': tab === 'improve' }"
                        @click="tab = 'improve'"
                        role="tab"
                        :aria-selected="tab === 'improve'">
                    {{ __('site.nav.community') }}
                </button>
                <button type="button"
                        class="impact-hub__tab"
                        :class="{ 'is-active': tab === 'reports' }"
                        @click="tab = 'reports'"
                        role="tab"
                        :aria-selected="tab === 'reports'">
                    {{ __('site.nav.social_impact_reports') }}
                </button>
            </div>

            {{-- Employee Empowerment --}}
            <div class="impact-hub__panel" x-show="tab === 'empower'" x-transition.opacity.duration.250ms role="tabpanel">
                <header class="impact-hub__panel-head">
                    <h3 class="impact-hub__panel-title">{{ __('site.nav.employee_empowerment') }}</h3>
                    <p class="impact-hub__panel-lead mb-0">{{ __('site.impact.empower_lead') }}</p>
                </header>

                @include('frontend.includes.luxury.artisan-journey')

                @if(($impacts ?? collect())->isNotEmpty())
                    <div class="row g-4 impact-hub__metrics">
                        @foreach ($impacts as $item)
                            <div class="col-md-6 col-lg-4">
                                <article class="impact-metric-card h-100">
                                    @if(!empty($item->value))
                                        <p class="impact-metric-card__value mb-1">{{ $item->value }}</p>
                                    @endif
                                    <h4 class="impact-metric-card__title">{{ $item->title }}</h4>
                                    @if(!empty($item->description))
                                        <p class="impact-metric-card__desc mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 140, '…') }}</p>
                                    @endif
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Community --}}
            <div class="impact-hub__panel impact-hub__panel--community" x-show="tab === 'improve'" x-transition.opacity.duration.250ms role="tabpanel">
                <header class="impact-hub__panel-head">
                    <h3 class="impact-hub__panel-title">{{ __('site.nav.community') }}</h3>
                    <p class="impact-hub__panel-lead mb-0">{{ __('site.impact.community_lead') }}</p>
                </header>

                @if(($initiatives ?? collect())->isEmpty())
                    <div class="impact-hub__empty text-center">
                        <p class="mb-0">{{ __('site.impact.community_empty') }}</p>
                    </div>
                @else
                    <div class="row g-4 impact-hub__initiatives">
                        @foreach ($initiatives as $initiative)
                            <div class="col-md-6 col-lg-4">
                                <article class="impact-initiative-card h-100">
                                    <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="impact-initiative-card__media d-block">
                                        @if(!empty($initiative->image))
                                            <img src="{{ asset('storage/' . ltrim($initiative->image, '/')) }}" alt="{{ $initiative->title }}" loading="lazy">
                                        @else
                                            <div class="impact-initiative-card__placeholder">{{ $initiative->title }}</div>
                                        @endif
                                    </a>
                                    <div class="impact-initiative-card__body">
                                        <h4 class="impact-initiative-card__title">
                                            <a href="{{ route('project', ['slug' => $initiative->slug]) }}">{{ $initiative->title }}</a>
                                        </h4>
                                        <p class="impact-initiative-card__excerpt mb-3">{{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($initiative->description ?? '')), 130, '…') }}</p>
                                        <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="impact-initiative-card__link">{{ __('site.home.updates_read') }} <span aria-hidden="true">→</span></a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif

                @include('frontend.includes.luxury.community-impact-split')
            </div>

            {{-- Social Impact Reports --}}
            <div class="impact-hub__panel impact-hub__panel--reports" x-show="tab === 'reports'" x-transition.opacity.duration.250ms role="tabpanel">
                <header class="impact-hub__panel-head">
                    <h3 class="impact-hub__panel-title">{{ __('site.nav.social_impact_reports') }}</h3>
                    <p class="impact-hub__panel-lead mb-0">{{ __('site.impact.reports_lead') }}</p>
                </header>

                @if($impactReports->isEmpty())
                    <div class="impact-hub__empty text-center">
                        <p class="mb-4">{{ __('site.impact.reports_empty') }}</p>
                        <a href="{{ route('impactReports') }}" class="tp-btn">{{ __('site.nav.social_impact_reports') }}</a>
                    </div>
                @else
                    <div class="row g-4 impact-hub__reports">
                        @foreach($impactReports as $report)
                            <div class="col-md-6 col-lg-4">
                                <article class="impact-report-card h-100">
                                    <h4 class="impact-report-card__title">{{ $report->heading }}</h4>
                                    @if(!empty($report->description))
                                        <p class="impact-report-card__desc">{{ \Illuminate\Support\Str::limit($report->description, 140, '…') }}</p>
                                    @endif
                                    <div class="impact-report-card__actions d-flex flex-wrap gap-2 mt-auto">
                                        <a href="{{ route('impactReportShow', ['slug' => $report->slug]) }}" class="tp-btn tp-btn--outline-dark">{{ __('site.home.updates_read') }}</a>
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
                    <div class="text-center mt-4 pt-2">
                        <a href="{{ route('impactReports') }}" class="impact-hub__view-all">{{ __('site.impact.reports_view_all') }} <span aria-hidden="true">→</span></a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="impact-page-cta" aria-labelledby="impact-page-cta-title">
        <div class="container">
            <div class="impact-page-cta__inner text-center">
                <p class="impact-page-cta__eyebrow">{{ __('site.impact.cta_eyebrow') }}</p>
                <h2 id="impact-page-cta-title" class="impact-page-cta__title">{{ __('site.impact.cta_title') }}</h2>
                <p class="impact-page-cta__lead mb-0">{{ __('site.impact.cta_lead') }}</p>
                <div class="impact-page-cta__actions d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('contacts') }}" class="tp-btn">{{ __('site.hero.cta_primary') }}</a>
                    <a href="{{ route('contacts') }}" class="impact-page-cta__ghost">{{ __('site.nav.contact') }}</a>
                </div>
            </div>
        </div>
    </section>

@endsection
