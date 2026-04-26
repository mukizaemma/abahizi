@php
    $ctaBgRow = $about ?? \App\Models\Background::firstOrEmpty();
    $missionRow = $mission ?? \App\Models\About::firstOrEmpty();
    /* Prefer a dedicated hero slot so this band differs from the quote strip below (often image1). */
    $ctaFile = $ctaBgRow->core_values_background ?? $ctaBgRow->image2 ?? $ctaBgRow->image ?? $ctaBgRow->image1 ?? '';
    $ctaBgUrl = $ctaFile !== ''
        ? (str_starts_with((string) $ctaFile, 'http')
            ? $ctaFile
            : asset('storage/images/' . ltrim($ctaFile, '/')))
        : '';
@endphp

<section class="programs-dual-cta {{ $ctaBgUrl ? '' : 'programs-dual-cta--no-bg' }}"
    @if($ctaBgUrl)
        style="--programs-cta-bg: url('{{ $ctaBgUrl }}'); min-height: 100vh; background-attachment: fixed;"
    @endif
    aria-labelledby="programs-dual-cta-heading"
>
    <div class="programs-dual-cta__overlay">
        <div class="programs-dual-cta__accent" aria-hidden="true"></div>
        <div class="programs-dual-cta__grain" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row g-4 g-md-3 align-items-stretch justify-content-center programs-dual-cta__row">
                <div class="col-12 col-md-5 col-xl-5">
                    <article class="programs-dual-cta-card programs-dual-cta-card--primary" style="background: rgba(8, 12, 20, 0.42); border-color: rgba(255, 255, 255, 0.28); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
                        <span class="programs-dual-cta-card__shine" aria-hidden="true"></span>
                        <span class="programs-dual-cta-card__icon" aria-hidden="true">
                            <i class="fas fa-bullseye"></i>
                        </span>
                        <span class="programs-dual-cta-card__label" style="color: #ffffff;">Mission</span>
                        <span class="programs-dual-cta-card__hint" style="color: rgba(255, 255, 255, 0.95);">
                            {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($missionRow->mission ?? 'To manufacture high-quality handbags and accessories for global brands while empowering our employees and uplifting the communities we serve.')), 230, '…') }}
                        </span>
                    </article>
                </div>
                <div class="col-md-2 d-none d-md-flex align-items-center justify-content-center programs-dual-cta__or-wrap">
                    <span class="programs-dual-cta__or" aria-hidden="true">&amp;</span>
                </div>
                <div class="col-12 col-md-5 col-xl-5">
                    <article class="programs-dual-cta-card programs-dual-cta-card--secondary" style="background: rgba(8, 12, 20, 0.42); border-color: rgba(255, 255, 255, 0.28); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
                        <span class="programs-dual-cta-card__shine" aria-hidden="true"></span>
                        <span class="programs-dual-cta-card__icon" aria-hidden="true">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="programs-dual-cta-card__label" style="color: #ffffff;">Vision</span>
                        <span class="programs-dual-cta-card__hint" style="color: rgba(255, 255, 255, 0.95);">
                            {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($missionRow->vision ?? 'To become Africa’s leading ethical manufacturing partner, known for exceptional craftsmanship and measurable social impact.')), 230, '…') }}
                        </span>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .programs-dual-cta {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
    @media (max-width: 991.98px) {
        .programs-dual-cta {
            min-height: auto;
            background-attachment: scroll !important;
        }
    }
</style>
