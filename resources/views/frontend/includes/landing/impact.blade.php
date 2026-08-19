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

    $quoteCards = collect($testimonials ?? [])->take(3)->map(function ($item) {
        $raw = html_entity_decode($item->testimony ?? '');
        $raw = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $raw);
        $raw = preg_replace('/<\s*\/p\s*>/i', "\n", $raw);
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($raw)));

        return [
            'quote' => \Illuminate\Support\Str::limit($plain, 140, '…'),
            'name' => $item->names ?? __('site.landing.testimonial_name_fallback'),
            'role' => $item->title ?? __('site.landing.testimonial_role_fallback'),
            'image' => ! empty($item->image)
                ? asset('storage/' . ltrim($item->image, '/'))
                : asset('assets/img/testimonial/author-1-1.png'),
        ];
    });

    if ($quoteCards->isEmpty()) {
        $quoteCards = collect([
            [
                'quote' => __('site.landing.testimonial_1_quote'),
                'name' => __('site.landing.testimonial_1_name'),
                'role' => __('site.landing.testimonial_1_role'),
                'image' => asset('assets/img/testimonial/author-1-1.png'),
            ],
            [
                'quote' => __('site.landing.testimonial_2_quote'),
                'name' => __('site.landing.testimonial_2_name'),
                'role' => __('site.landing.testimonial_2_role'),
                'image' => asset('assets/img/testimonial/author-1-2.png'),
            ],
        ]);
    } elseif ($quoteCards->count() === 1) {
        $quoteCards->push([
            'quote' => __('site.landing.testimonial_2_quote'),
            'name' => __('site.landing.testimonial_2_name'),
            'role' => __('site.landing.testimonial_2_role'),
            'image' => asset('assets/img/testimonial/author-1-2.png'),
        ]);
    }
@endphp

<section
    class="lh-impact lh-impact--parallax"
    id="lh-impact"
    aria-labelledby="lh-impact-title"
    data-lh-counter-section
    data-lux-parallax
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

        <div class="lh-impact__quotes">
            @foreach($quoteCards as $index => $card)
                <article class="lh-quote lh-reveal" style="transition-delay: {{ $index * 0.1 }}s">
                    <div class="lh-quote__photo">
                        <img src="{{ $card['image'] }}" alt="{{ $card['name'] }}" loading="lazy" decoding="async">
                    </div>
                    <div>
                        <p class="lh-quote__text">“{{ $card['quote'] }}”</p>
                        <p class="lh-quote__meta">
                            {{ $card['name'] }}
                            <span class="lh-quote__role">{{ $card['role'] }}</span>
                        </p>
                    </div>
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
