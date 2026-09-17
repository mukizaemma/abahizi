@php
    $pillars = $pillars ?? \App\Support\ImpactPillars::items(8);
    $heading = $heading ?? 'How every order helps';
    $lead = $lead ?? 'Health, education, care, and family programmes funded by the work of our cooperative.';
    $tone = $tone ?? 'light';
    $sectionId = $sectionId ?? 'impact-pillars';
@endphp
@if(count($pillars))
<section class="impact-pillars impact-pillars--{{ $tone }}" id="{{ $sectionId }}" aria-labelledby="{{ $sectionId }}-title">
    <div class="container">
        <header class="impact-pillars__intro text-center">
            <p class="lh-eyebrow mb-2">Impact pillars</p>
            <h2 id="{{ $sectionId }}-title" class="impact-pillars__title">{{ $heading }}</h2>
            @if($lead)
                <p class="impact-pillars__lead">{{ $lead }}</p>
            @endif
        </header>
        <div class="impact-pillars__grid" style="--pillar-count: {{ min(count($pillars), 4) }}">
            @foreach($pillars as $index => $pillar)
                <article class="impact-pillar-card{{ !empty($pillar['image']) ? ' has-photo' : '' }}">
                    <div class="impact-pillar-card__media">
                        @if(!empty($pillar['image']))
                            <img src="{{ $pillar['image'] }}" alt="" loading="lazy" decoding="async">
                        @endif
                        <span class="impact-pillar-card__icon" aria-hidden="true"><i class="fas {{ $pillar['icon'] }}"></i></span>
                        @if(($pillar['value'] ?? '') !== '')
                            <p class="impact-pillar-card__value">{{ $pillar['value'] }}</p>
                        @endif
                    </div>
                    <div class="impact-pillar-card__body">
                        <h3 class="impact-pillar-card__title">{{ $pillar['title'] }}</h3>
                        @if(($pillar['text'] ?? '') !== '')
                            <p class="impact-pillar-card__text">{{ $pillar['text'] }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
