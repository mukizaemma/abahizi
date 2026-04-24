@extends('layouts.frontbase')

@section('title', 'Our Factory')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Factory',
    ])

    @php
        $parseItems = static function (?string $raw): array {
            $txt = trim(strip_tags(html_entity_decode((string) $raw)));
            if ($txt === '') {
                return [];
            }

            $parts = preg_split('/[\r\n,•]+/u', $txt) ?: [];
            $items = [];
            foreach ($parts as $part) {
                $line = trim($part);
                if ($line !== '') {
                    $items[] = $line;
                }
            }

            return $items;
        };

        $factoryDescription = trim((string) ($about->factory_description ?? ''));
        $factoryServices = $parseItems($about->factory_services ?? '');
        $factoryServicesSubitems = $parseItems($about->factory_services_subitems ?? '');
        $trainingFacilities = $parseItems($about->factory_training_facilities ?? '');
        $trainingFacilitiesSubitems = $parseItems($about->factory_training_facilities_subitems ?? '');
        $communityImpactRaw = trim((string) ($about->factory_community_impact ?? ''));
        $communityImpactSubitems = $parseItems($about->factory_community_impact_subitems ?? '');
    @endphp

    <section class="page-standalone grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-xl-10 col-xxl-9">
                    <article class="page-standalone-card">
                        <header class="page-standalone-card__head">
                            <span class="page-standalone-card__icon" aria-hidden="true"><i class="flaticon-settings"></i></span>
                            <div>
                                <p class="page-standalone-card__eyebrow">Production &amp; training</p>
                                <h2 class="page-standalone-card__title mb-0">Our factory</h2>
                            </div>
                        </header>
                        <div class="page-standalone-card__body postbox__text">
                            <p class="lead mb-0" style="font-size: 1.15rem; line-height: 1.75; color: #333;">
                                {!! $factoryDescription !== '' ? $factoryDescription : 'Our production space brings together tailoring workshops, quality craftsmanship, and hands-on learning where trainees and staff create products that carry our mission forward.' !!}
                            </p>
                        </div>
                    </article>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <article class="page-standalone-card h-100 factory-feature-card">
                        @if(!empty($about->factory_services_image))
                            <div class="factory-feature-card__media">
                                <img src="{{ asset('storage/images/' . $about->factory_services_image) }}" alt="Factory services">
                            </div>
                        @endif
                        <header class="page-standalone-card__head">
                            <span class="page-standalone-card__icon" aria-hidden="true"><i class="fas fa-industry"></i></span>
                            <div>
                                <p class="page-standalone-card__eyebrow">Services</p>
                                <h3 class="page-standalone-card__title mb-0">Factory services</h3>
                            </div>
                        </header>
                        @if(count($factoryServices) > 0)
                            <ul class="list-unstyled mb-0 factory-points-list">
                                @foreach($factoryServices as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0 text-muted">Add factory services from admin to display them here.</p>
                        @endif
                        <button class="btn btn-outline-secondary btn-sm mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#factory-services-more" aria-expanded="false" aria-controls="factory-services-more">
                            View more
                        </button>
                        <div class="collapse mt-3" id="factory-services-more">
                            @if(count($factoryServicesSubitems) > 0)
                                <ul class="list-unstyled mb-0 factory-subitems-list">
                                    @foreach($factoryServicesSubitems as $sub)
                                        <li>{{ $sub }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0 small">Add sub-items from admin to show more details.</p>
                            @endif
                        </div>
                    </article>
                </div>

                <div class="col-lg-4">
                    <article class="page-standalone-card h-100 factory-feature-card">
                        @if(!empty($about->factory_community_impact_image))
                            <div class="factory-feature-card__media">
                                <img src="{{ asset('storage/images/' . $about->factory_community_impact_image) }}" alt="Community impact">
                            </div>
                        @endif
                        <header class="page-standalone-card__head">
                            <span class="page-standalone-card__icon" aria-hidden="true"><i class="fas fa-people-group"></i></span>
                            <div>
                                <p class="page-standalone-card__eyebrow">Community</p>
                                <h3 class="page-standalone-card__title mb-0">Community impact</h3>
                            </div>
                        </header>
                        <div class="page-standalone-card__body postbox__text">
                            {!! $communityImpactRaw !== '' ? $communityImpactRaw : '<p class="mb-0">Our factory operations are designed to improve livelihoods, strengthen households, and build long-term community resilience.</p>' !!}
                        </div>
                        <button class="btn btn-outline-secondary btn-sm mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#factory-impact-more" aria-expanded="false" aria-controls="factory-impact-more">
                            View more
                        </button>
                        <div class="collapse mt-3" id="factory-impact-more">
                            @if(count($communityImpactSubitems) > 0)
                                <ul class="list-unstyled mb-0 factory-subitems-list">
                                    @foreach($communityImpactSubitems as $sub)
                                        <li>{{ $sub }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0 small">Add sub-items from admin to show more details.</p>
                            @endif
                        </div>
                    </article>
                </div>

                <div class="col-lg-4">
                    <article class="page-standalone-card h-100 factory-feature-card">
                        @if(!empty($about->factory_training_facilities_image))
                            <div class="factory-feature-card__media">
                                <img src="{{ asset('storage/images/' . $about->factory_training_facilities_image) }}" alt="Training facilities">
                            </div>
                        @endif
                        <header class="page-standalone-card__head">
                            <span class="page-standalone-card__icon" aria-hidden="true"><i class="fas fa-graduation-cap"></i></span>
                            <div>
                                <p class="page-standalone-card__eyebrow">Learning &amp; growth</p>
                                <h3 class="page-standalone-card__title mb-0">Training facilities for creativity and upskilling</h3>
                            </div>
                        </header>
                        @if(count($trainingFacilities) > 0)
                            <ul class="list-unstyled mb-0 factory-points-list">
                                @foreach($trainingFacilities as $facility)
                                    <li>{{ $facility }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0 text-muted">Add training facilities from admin to display this section.</p>
                        @endif
                        <button class="btn btn-outline-secondary btn-sm mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#factory-training-more" aria-expanded="false" aria-controls="factory-training-more">
                            View more
                        </button>
                        <div class="collapse mt-3" id="factory-training-more">
                            @if(count($trainingFacilitiesSubitems) > 0)
                                <ul class="list-unstyled mb-0 factory-subitems-list">
                                    @foreach($trainingFacilitiesSubitems as $sub)
                                        <li>{{ $sub }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0 small">Add sub-items from admin to show more details.</p>
                            @endif
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <style>
        .factory-points-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.7rem;
            font-size: 1rem;
            line-height: 1.7;
            color: #2f2f2f;
        }

        .factory-points-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.65rem;
            width: 0.55rem;
            height: 0.55rem;
            border-radius: 50%;
            background: var(--brand-primary, #fad200);
        }

        .factory-feature-card__media {
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1rem;
            border: 1px solid rgba(44, 44, 44, 0.08);
            background: #f2f2f2;
        }

        .factory-feature-card__media img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .factory-subitems-list li {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 0.45rem;
            color: #434343;
            font-size: 0.94rem;
            line-height: 1.6;
        }

        .factory-subitems-list li::before {
            content: "-";
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>

@endsection
