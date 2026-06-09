@php
    $impactPillars = [
        [
            'icon' => 'fa-people-group',
            'title' => __('site.factory.impact_pillars.jobs_title'),
            'desc' => __('site.factory.impact_pillars.jobs_desc'),
            'stat' => $about->artisans_count ?? ($about->jobs_created ?? '270+'),
            'stat_label' => __('site.stats.artisans'),
        ],
        [
            'icon' => 'fa-house-heart',
            'title' => __('site.factory.impact_pillars.families_title'),
            'desc' => __('site.factory.impact_pillars.families_desc'),
            'stat' => $about->families_impacted ?? '1,500+',
            'stat_label' => __('site.stats.families'),
        ],
        [
            'icon' => 'fa-graduation-cap',
            'title' => __('site.factory.impact_pillars.training_title'),
            'desc' => __('site.factory.impact_pillars.training_desc'),
            'stat' => $about->training_hours ?? '20,000+',
            'stat_label' => __('site.stats.training'),
        ],
    ];

    $milestones = [
        __('site.factory.journey.training'),
        __('site.factory.journey.skills'),
        __('site.factory.journey.employment'),
        __('site.factory.journey.ownership'),
    ];
@endphp

<section class="lux-section factory-impact grey-bg" aria-labelledby="factory-impact-title">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
            <h2 id="factory-impact-title" class="lux-section-head__title mb-3">{{ __('site.factory.impact_title') }}</h2>
            <p class="lux-lead mb-0 mx-auto" style="max-width: 42rem;">{{ __('site.factory.impact_lead') }}</p>
        </div>

        <div class="row g-4 mb-4 mb-lg-5">
            @foreach($impactPillars as $i => $pillar)
                <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.08, 2) }}s">
                    <article class="lux-card lux-card--stat h-100">
                        <span class="lux-card__icon" aria-hidden="true"><i class="fas {{ $pillar['icon'] }}"></i></span>
                        <p class="lux-card__stat">{{ $pillar['stat'] }}</p>
                        <p class="lux-card__stat-label">{{ $pillar['stat_label'] }}</p>
                        <h3 class="lux-card__title">{{ $pillar['title'] }}</h3>
                        <p class="lux-card__desc mb-0">{{ $pillar['desc'] }}</p>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="factory-impact__journey lux-card">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5 lux-section-head lux-section-head--solo">
                    <h3 class="factory-impact__journey-title">{{ __('site.factory.journey_title') }}</h3>
                    <p class="factory-impact__journey-lead mb-0">{{ __('site.factory.journey_lead') }}</p>
                </div>
                <div class="col-lg-7">
                    <ol class="factory-impact__milestones">
                        @foreach($milestones as $i => $milestone)
                            <li class="factory-impact__milestone">
                                <span class="factory-impact__milestone-index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="factory-impact__milestone-text">{{ $milestone }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 mt-lg-5">
            <a href="{{ route('impactPage') }}" class="tp-btn tp-btn--lux">{{ __('site.factory.impact_cta') }} <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>
