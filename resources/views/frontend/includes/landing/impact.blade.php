@php
    use App\Support\SectionBackgroundService;

    $impactAbout = $about ?? \App\Models\Background::firstOrEmpty();
    $impactBgUrl = SectionBackgroundService::resolve('impact_cta_background', $impactAbout)
        ?? asset('assets/img/slider/slider-bg-3-2.jpg');

    $impactItems = collect($homeImpacts ?? [])->filter(function ($item) {
        return trim((string) ($item->title ?? '')) !== '' || trim((string) ($item->value ?? '')) !== '';
    });

    if ($impactItems->isNotEmpty()) {
        $stats = $impactItems->map(function ($item) {
            return [
                'value' => trim((string) ($item->value ?? '')),
                'label' => trim((string) ($item->title ?? '')),
            ];
        })->values()->all();
    } else {
        $stats = [
            [
                'value' => $impactAbout->families_impacted ?? '2,000+',
                'label' => __('site.landing.stat_families'),
            ],
            [
                'value' => $impactAbout->artisans_count ?? ($impactAbout->jobs_created ?? '260+'),
                'label' => __('site.landing.stat_jobs'),
            ],
            [
                'value' => $impactAbout->handbags_exported ?? '310,000+',
                'label' => __('site.landing.stat_handbags'),
            ],
            [
                'value' => $impactAbout->training_hours ?? '20,000+',
                'label' => __('site.landing.stat_training'),
            ],
        ];
    }
@endphp

<section
    class="lh-impact lh-impact--parallax"
    id="lh-impact"
    aria-labelledby="lh-impact-title"
    data-lh-counter-section
    data-lux-parallax
    data-lux-parallax-strength="strong"
>
    <div class="lh-impact__parallax" data-lux-parallax-layer aria-hidden="true">
        <img
            class="lh-impact__parallax-img"
            src="{{ $impactBgUrl }}"
            alt=""
            decoding="async"
            fetchpriority="low"
        >
    </div>
    <div class="lh-impact__bg" aria-hidden="true"></div>
    <div class="container lh-impact__inner">
        <h2 id="lh-impact-title" class="lh-impact__title lh-reveal">
            {!! __('site.landing.impact_title_html') !!}
        </h2>

        <div class="lh-impact__counters">
            @foreach($stats as $index => $stat)
                @php
                    $rawValue = trim((string) ($stat['value'] ?? ''));
                    $digits = preg_replace('/[^\d]/', '', $rawValue);
                    $counterTarget = $digits !== '' ? (int) $digits : 0;
                @endphp
                <article class="lh-impact__stat lh-reveal" style="transition-delay: {{ $index * 0.07 }}s">
                    <span class="lh-impact__index" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    @if($rawValue !== '')
                        <p
                            class="lh-impact__value"
                            data-lh-counter-target="{{ $counterTarget }}"
                            data-lh-counter-final="{{ $rawValue }}"
                        >{{ $counterTarget > 0 ? '0' : $rawValue }}</p>
                    @endif
                    @if(! empty($stat['label']))
                        <p class="lh-impact__label">{{ $stat['label'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="lh-impact__actions lh-reveal">
            <a href="{{ route('impactPage') }}" class="lh-btn lh-btn--primary">{{ __('site.landing.impact_cta_explore') }}</a>
            <a href="{{ route('impactCommunity') }}" class="lh-btn lh-btn--ghost">{{ __('site.landing.impact_cta_community') }}</a>
            <a href="{{ route('impactReports') }}" class="lh-btn lh-btn--ghost">{{ __('site.landing.impact_cta_reports') }}</a>
        </div>
    </div>
</section>
