@extends('layouts.frontbase')

@section('title', __('site.impact.title'))

@section('content')

    @php
        $fallbackImage = asset('assets/img/slider/slider-3-1.jpg');

        $empowerImage = ! empty($about->factory_services_image ?? null)
            ? asset('storage/images/' . $about->factory_services_image)
            : (! empty($about->image2 ?? null)
                ? asset('storage/images/' . $about->image2)
                : $fallbackImage);

        $communityImage = $fallbackImage;
        if (($hubCommunityImage ?? null) && ! empty($hubCommunityImage->image)) {
            $communityImage = asset('storage/' . ltrim($hubCommunityImage->image, '/'));
        } elseif (! empty($about->image1 ?? null)) {
            $communityImage = asset('storage/images/' . $about->image1);
        }

        $reportsImage = $fallbackImage;
        if (($hubReportImage ?? null) && ($hubReportImage->images ?? collect())->isNotEmpty()) {
            $firstImg = $hubReportImage->images->first();
            if (! empty($firstImg->image ?? null)) {
                $reportsImage = $firstImg->imageUrl();
            }
        } elseif (! empty($about->image ?? null)) {
            $reportsImage = asset('storage/images/' . $about->image);
        }

        $hubCards = [
            [
                'title' => __('site.nav.employee_empowerment'),
                'desc' => __('site.impact.empower_lead'),
                'image' => $empowerImage,
                'url' => route('impactEmployeeEmpowerment'),
            ],
            [
                'title' => __('site.nav.community'),
                'desc' => __('site.impact.community_lead'),
                'image' => $communityImage,
                'url' => route('impactCommunity'),
            ],
            [
                'title' => __('site.nav.social_impact_reports'),
                'desc' => __('site.impact.reports_lead'),
                'image' => $reportsImage,
                'url' => route('impactReports'),
            ],
        ];
    @endphp

    @include('frontend.includes.page-header', [
        'pageKey' => 'impact',
        'title' => __('site.impact.title'),
        'caption' => __('site.impact.caption'),
    ])

    <section class="lux-section impact-hub" aria-label="{{ __('site.impact.hub_title') }}">
        <div class="container">
            <div class="row g-4 g-lg-4">
                @foreach($hubCards as $i => $card)
                    <div class="col-12 col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                        <article class="impact-hub-card h-100">
                            <a href="{{ $card['url'] }}" class="impact-hub-card__media d-block">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                            </a>
                            <div class="impact-hub-card__body">
                                <h2 class="impact-hub-card__title h3">
                                    <a href="{{ $card['url'] }}">{{ $card['title'] }}</a>
                                </h2>
                                <p class="impact-hub-card__desc mb-0">{{ $card['desc'] }}</p>
                                <a href="{{ $card['url'] }}" class="impact-hub-card__link">
                                    {{ __('site.impact.hub_card_cta') }} <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('frontend.includes.bottom')

@endsection
