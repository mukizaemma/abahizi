@php
    use App\Support\SectionBackgroundService;

    $impactAbout = $about ?? \App\Models\Background::firstOrEmpty();

    $defaultPillars = [
        ['icon' => 'fa-briefcase-medical', 'title' => __('site.landing.pillar_1_title'), 'text' => __('site.landing.pillar_1_text')],
        ['icon' => 'fa-graduation-cap', 'title' => __('site.landing.pillar_2_title'), 'text' => __('site.landing.pillar_2_text')],
        ['icon' => 'fa-heart-pulse', 'title' => __('site.landing.pillar_3_title'), 'text' => __('site.landing.pillar_3_text')],
        ['icon' => 'fa-people-roof', 'title' => __('site.landing.pillar_4_title'), 'text' => __('site.landing.pillar_4_text')],
    ];

    $cmsPillars = collect($homeImpacts ?? [])
        ->filter(fn ($item) => trim((string) ($item->title ?? '')) !== '')
        ->take(4)
        ->values();

    $pillarIcons = ['fa-briefcase-medical', 'fa-graduation-cap', 'fa-heart-pulse', 'fa-people-roof'];

    $pillars = $cmsPillars->isNotEmpty()
        ? $cmsPillars->map(function ($item, $index) use ($pillarIcons, $defaultPillars) {
            $text = trim(strip_tags((string) ($item->description ?? $item->value ?? '')));
            if ($text === '') {
                $text = $defaultPillars[$index]['text'] ?? '';
            }

            return [
                'icon' => $pillarIcons[$index] ?? 'fa-heart',
                'title' => trim((string) $item->title),
                'text' => \Illuminate\Support\Str::limit($text, 110, '…'),
            ];
        })->all()
        : $defaultPillars;

    $photoSources = collect();
    if (isset($homeGallery)) {
        foreach ($homeGallery as $img) {
            if (! empty($img->image)) {
                $photoSources->push(asset('storage/images/gallery/' . ltrim($img->image, '/')));
            }
        }
    }
    foreach (['image1', 'image2', 'image3', 'image'] as $field) {
        if (! empty($impactAbout->{$field} ?? null)) {
            $photoSources->push(asset('storage/images/' . ltrim($impactAbout->{$field}, '/')));
        }
    }
    $resolvedBg = SectionBackgroundService::resolve('impact_cta_background', $impactAbout);
    if ($resolvedBg) {
        $photoSources->push($resolvedBg);
    }
    $photoSources = $photoSources->filter()->unique()->values();
    while ($photoSources->count() < 4) {
        $photoSources->push(asset('assets/img/slider/slider-bg-3-2.jpg'));
    }
    $impactPhotos = $photoSources->take(4);
@endphp

<section class="lh-impact" id="lh-impact" aria-labelledby="lh-impact-title">
    <div class="container lh-impact__inner">
        <div class="lh-impact__intro lh-reveal">
            <h2 id="lh-impact-title" class="lh-impact__title">
                {!! __('site.landing.impact_title_html') !!}
            </h2>
            <p class="lh-impact__lead">{{ __('site.landing.impact_lead') }}</p>
        </div>

        <div class="lh-impact__pillars">
            @foreach($pillars as $index => $pillar)
                <article class="lh-pillar lh-reveal" style="transition-delay: {{ $index * 0.07 }}s">
                    <span class="lh-pillar__icon" aria-hidden="true"><i class="fas {{ $pillar['icon'] }}"></i></span>
                    <h3 class="lh-pillar__title">{{ $pillar['title'] }}</h3>
                    <p class="lh-pillar__text">{{ $pillar['text'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="lh-impact__photos">
            @foreach($impactPhotos as $index => $src)
                <figure class="lh-impact-photo lh-reveal" style="transition-delay: {{ $index * 0.08 }}s">
                    <img src="{{ $src }}" alt="" loading="lazy" decoding="async">
                </figure>
            @endforeach
        </div>

        <div class="lh-impact__actions lh-reveal">
            <a href="{{ route('impactPage') }}" class="lh-btn lh-btn--primary">{{ __('site.landing.impact_cta_explore') }}</a>
            <a href="{{ route('impactCommunity') }}" class="lh-btn lh-btn--ghost">{{ __('site.landing.impact_cta_community') }}</a>
        </div>
    </div>
</section>
