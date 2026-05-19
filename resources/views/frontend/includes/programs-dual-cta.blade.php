@php
    $ctaBgRow = $about ?? \App\Models\Background::firstOrEmpty();
    $missionRow = $mission ?? \App\Models\About::firstOrEmpty();
    $ctaFile = $ctaBgRow->core_values_background ?? $ctaBgRow->image2 ?? $ctaBgRow->image ?? $ctaBgRow->image1 ?? '';
    $ctaBgUrl = $ctaFile !== ''
        ? (str_starts_with((string) $ctaFile, 'http')
            ? $ctaFile
            : asset('storage/images/' . ltrim($ctaFile, '/')))
        : '';
@endphp

<section
    class="programs-dual-cta {{ $ctaBgUrl ? '' : 'programs-dual-cta--no-bg' }}"
    @if($ctaBgUrl) style="--programs-cta-bg: url('{{ $ctaBgUrl }}');" @endif
    aria-label="Mission, vision, and impact"
>
    <div class="programs-dual-cta__overlay">
        <div class="container position-relative">
            <div class="programs-dual-cta__layout">
                <article class="programs-dual-cta-card programs-dual-cta-card--primary">
                    <header class="programs-dual-cta-card__heading">
                        <span class="programs-dual-cta-card__icon" aria-hidden="true">
                            <i class="fas fa-bullseye"></i>
                        </span>
                        <h3 class="programs-dual-cta-card__label">Mission</h3>
                    </header>
                    <p class="programs-dual-cta-card__hint">
                        {{ strip_tags(html_entity_decode($missionRow->mission ?? 'To manufacture high-quality handbags and accessories for global brands while empowering our employees and uplifting the communities we serve.')) }}
                    </p>
                </article>

                <span class="programs-dual-cta__or" aria-hidden="true">&amp;</span>

                <article class="programs-dual-cta-card programs-dual-cta-card--secondary">
                    <header class="programs-dual-cta-card__heading">
                        <span class="programs-dual-cta-card__icon" aria-hidden="true">
                            <i class="fas fa-eye"></i>
                        </span>
                        <h3 class="programs-dual-cta-card__label">Vision</h3>
                    </header>
                    <p class="programs-dual-cta-card__hint">
                        {{ strip_tags(html_entity_decode($missionRow->vision ?? 'To become Africa’s leading ethical manufacturing partner, known for exceptional craftsmanship and measurable social impact.')) }}
                    </p>
                </article>

                @if(!empty($navProgramOurImpact))
                    <a
                        class="tp-btn programs-dual-cta__impact-btn"
                        href="{{ route('programShow', ['slug' => $navProgramOurImpact->slug]) }}"
                    >
                        Our Impacts <span aria-hidden="true">→</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
