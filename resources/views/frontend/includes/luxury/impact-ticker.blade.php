@php
    $stats = [
        [
            'value' => $about->handbags_exported ?? '310,000+',
            'label' => __('site.stats.handbags'),
        ],
        [
            'value' => $about->artisans_count ?? ($about->jobs_created ?? '260+'),
            'label' => __('site.stats.artisans'),
        ],
        [
            'value' => $about->families_impacted ?? '2,000+',
            'label' => __('site.stats.families'),
        ],
        [
            'value' => $about->training_hours ?? '20,000+',
            'label' => __('site.stats.training'),
        ],
    ];
@endphp

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
