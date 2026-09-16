@php
    $ways = $activity->publicInvolvementWays();
    $pickWay = static function (array $ways, string $needle) {
        foreach ($ways as $way) {
            $hay = strtolower(($way['slug'] ?? '') . ' ' . ($way['label'] ?? ''));
            if (str_contains($hay, $needle)) {
                return $way;
            }
        }

        return $ways[0] ?? null;
    };
    $volunteerWay = $pickWay($ways, 'volunteer');
    $partnerWay = $pickWay($ways, 'partner');
    if ($partnerWay && $volunteerWay && ($partnerWay['slug'] ?? '') === ($volunteerWay['slug'] ?? '') && count($ways) > 1) {
        $partnerWay = $ways[1];
    }
@endphp

<section class="program-cta lux-section" aria-labelledby="program-cta-title">
    <div class="container">
        <div class="program-cta__inner">
            <p class="program-cta__eyebrow">{{ __('site.initiative.cta_eyebrow') }}</p>
            <h2 id="program-cta-title" class="program-cta__title">{{ __('site.initiative.support_title', ['name' => $activity->title]) }}</h2>
            <p class="program-cta__lead">{{ __('site.initiative.support_lead') }}</p>
            <div class="program-cta__grid">
                @if($volunteerWay)
                    <article class="program-cta__card">
                        <h3>{{ __('site.initiative.volunteer_title') }}</h3>
                        <p>{{ __('site.initiative.volunteer_text') }}</p>
                        <button
                            type="button"
                            class="tp-btn tp-btn--lux"
                            data-bs-toggle="modal"
                            data-bs-target="#getInvolvedModal"
                            data-involve-way="{{ $volunteerWay['slug'] }}"
                        >
                            {{ __('site.initiative.volunteer_btn') }}
                        </button>
                    </article>
                @endif
                @if($partnerWay)
                    <article class="program-cta__card">
                        <h3>{{ __('site.initiative.partner_title') }}</h3>
                        <p>{{ __('site.initiative.partner_text') }}</p>
                        <button
                            type="button"
                            class="tp-btn tp-btn--outline-dark"
                            data-bs-toggle="modal"
                            data-bs-target="#getInvolvedModal"
                            data-involve-way="{{ $partnerWay['slug'] }}"
                        >
                            {{ __('site.initiative.partner_btn') }}
                        </button>
                    </article>
                @endif
            </div>
        </div>
    </div>
</section>
