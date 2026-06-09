@php
    $mapOnly = (bool) ($mapOnly ?? false);
    $initiatives = $mapOnly ? [] : [
        'Nutrition programs for families and children',
        'Computer literacy & digital skills',
        'Mental health and psychosocial support',
        'Masoro Health Center community services',
        'Isooko Community Development (9,000+ members)',
    ];
    $mapEmbed = trim((string) ($setting->google_map_embed_code ?? ''));
@endphp

<section class="lux-community {{ $mapOnly ? 'lux-community--map-only' : '' }}" aria-labelledby="lux-community-heading">
    <div class="container">
        @unless($mapOnly)
            <div class="lux-section-head lux-section-head--solo text-center mb-4">
                <h2 id="lux-community-heading" class="lux-section-head__title">{{ __('site.impact.community_title') }}</h2>
            </div>
        @endunless
        <div class="row g-4 g-lg-5 align-items-stretch">
            @unless($mapOnly)
                <div class="col-lg-5">
                    <ul class="lux-community__list">
                        @foreach($initiatives as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endunless
            <div class="{{ $mapOnly ? 'col-12' : 'col-lg-7' }}">
                @if($mapOnly)
                    <div class="lux-section-head lux-section-head--solo text-center mb-4">
                        <h2 id="lux-community-heading" class="lux-section-head__title">{{ __('site.impact.community_map_title') }}</h2>
                    </div>
                @endif
                <div class="lux-community__map-card">
                    @if($mapEmbed !== '')
                        <div class="lux-community__map ratio ratio-16x9">
                            {!! $mapEmbed !!}
                        </div>
                    @else
                        <div class="lux-community__map lux-community__map--placeholder ratio ratio-16x9">
                            <div class="lux-community__map-fallback">
                                <p class="mb-1 fw-semibold">Masoro · Rulindo District</p>
                                <p class="mb-0 text-muted small">Masoro Health Center · Isooko Community Development</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
