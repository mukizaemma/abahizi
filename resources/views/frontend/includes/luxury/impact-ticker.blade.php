@php
    $stats = \App\Support\ImpactStats::items($about ?? null);
@endphp

@if(($stats ?? []) !== [])
<section class="lux-ticker" data-lux-counter-section aria-label="Impact statistics">
    <div class="container">
        <div class="lux-ticker__grid">
            @foreach($stats as $stat)
                @php
                    $rawValue = trim((string) ($stat['value'] ?? ''));
                    $digits = preg_replace('/[^\d]/', '', $rawValue);
                    $counterTarget = $digits !== '' ? (int) $digits : 0;
                @endphp
                <article class="lux-ticker__stat wow tpfadeUp" data-wow-duration=".8s">
                    <p class="lux-ticker__value"
                       data-lux-counter-target="{{ $counterTarget }}"
                       data-lux-counter-final="{{ $rawValue }}">{{ $counterTarget > 0 ? '0' : $rawValue }}</p>
                    <p class="lux-ticker__label">{{ $stat['label'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
