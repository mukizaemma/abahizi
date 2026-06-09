@php
    $aboutDescPlain = strip_tags(html_entity_decode($about->description ?? ''));
    $aboutLead = \Illuminate\Support\Str::limit($aboutDescPlain, 280, '…');
    $problemText = \Illuminate\Support\Str::limit(
        strip_tags(html_entity_decode($about->problem_statement ?? 'Many skilled women in Rwanda face limited access to stable employment and professional growth, while buyers worldwide seek ethical manufacturing partners who deliver both quality and impact.')),
        220,
        '…'
    );
    $solutionText = \Illuminate\Support\Str::limit(
        strip_tags(html_entity_decode($about->solution_statement ?? 'Abahizi CBC bridges this gap—combining premium bag manufacturing with holistic support for employees and the communities we serve.')),
        220,
        '…'
    );
@endphp

<section class="home-about-merged grey-bg pt-80 pb-70" aria-labelledby="home-about-merged-title">
    <div class="container">
        <div class="row justify-content-center mb-4 mb-lg-5">
            <div class="col-12 col-lg-10 col-xl-8 text-center">
                <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.about_eyebrow') }}</p>
                <h2 id="home-about-merged-title" class="lux-section-head__title mb-3">{{ __('site.home.about_title') }}</h2>
                <p class="home-about-merged__lead mb-0">{{ $aboutLead }}</p>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-md-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                <article class="home-about-merged__card h-100">
                    <span class="home-about-merged__card-icon" aria-hidden="true"><i class="fas fa-seedling"></i></span>
                    <h3 class="home-about-merged__card-title">The challenge</h3>
                    <p class="home-about-merged__card-text mb-0">{{ $problemText }}</p>
                </article>
            </div>
            <div class="col-md-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                <article class="home-about-merged__card home-about-merged__card--accent h-100">
                    <span class="home-about-merged__card-icon" aria-hidden="true"><i class="fas fa-hand-holding-heart"></i></span>
                    <h3 class="home-about-merged__card-title">Our response</h3>
                    <p class="home-about-merged__card-text mb-0">{{ $solutionText }}</p>
                </article>
            </div>
        </div>

        <div class="text-center mt-4 mt-lg-5">
            <a class="tp-btn" href="{{ route('backgroundDetails') }}">{{ __('site.home.about_cta') }} <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>
