@php
    use App\Support\SectionBackgroundService;

    $impactAbout = $about ?? \App\Models\Background::firstOrEmpty();
    $pillars = \App\Support\ImpactPillars::items(4);

    $photoSources = collect();
    if (isset($homeGallery)) {
        foreach ($homeGallery as $img) {
            if (method_exists($img, 'url') && $img->url()) {
                $photoSources->push($img->url());
            } elseif (! empty($img->image)) {
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
    foreach ($pillars as $pillar) {
        if (! empty($pillar['image'])) {
            $photoSources->push($pillar['image']);
        }
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
                {!! \App\Support\SiteCopy::impactTitleHtml() !!}
            </h2>
            <p class="lh-impact__lead">{{ \App\Support\SiteCopy::get('impact_lead') }}</p>
        </div>

        <div class="lh-impact__pillars">
            @foreach($pillars as $index => $pillar)
                <article class="lh-pillar lh-reveal{{ !empty($pillar['image']) ? ' lh-pillar--photo' : '' }}" style="transition-delay: {{ $index * 0.07 }}s">
                    <div class="lh-pillar__media">
                        @if(!empty($pillar['image']))
                            <img src="{{ $pillar['image'] }}" alt="" loading="lazy" decoding="async">
                        @endif
                        <span class="lh-pillar__icon" aria-hidden="true"><i class="fas {{ $pillar['icon'] }}"></i></span>
                    </div>
                    @if(($pillar['value'] ?? '') !== '')
                        <p class="lh-pillar__value">{{ $pillar['value'] }}</p>
                    @endif
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
            <a href="{{ route('impactPage') }}#impact-pillars" class="lh-btn lh-btn--primary">{{ \App\Support\SiteCopy::get('impact_cta_explore') }}</a>
            <a href="{{ route('impactCommunity') }}" class="lh-btn lh-btn--ghost">{{ \App\Support\SiteCopy::get('impact_cta_community') }}</a>
        </div>
    </div>
</section>
