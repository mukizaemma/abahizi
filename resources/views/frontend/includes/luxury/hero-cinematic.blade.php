@php
    $posterSlide = ($slides ?? collect())->first();
    $posterFromSlide = $posterSlide
        ? \App\Models\Slide::publicImageUrl($posterSlide->image)
        : null;
    $posterFromSetting = ! empty($setting->hero_poster ?? null)
        ? asset('storage/images/' . ltrim($setting->hero_poster, '/'))
        : null;
    $posterUrl = $posterFromSetting ?? $posterFromSlide ?? asset('assets/img/slider/slider-3-1.jpg');

    $videoUrl = trim((string) ($setting->hero_video_url ?? ''));
    if ($videoUrl !== '' && ! str_starts_with($videoUrl, 'http')) {
        $videoUrl = asset('storage/videos/' . ltrim($videoUrl, '/'));
    }

    $headline = trim((string) ($setting->hero_headline ?? ''));
    if ($headline === '') {
        $headline = __('site.hero.title');
    }

    $subheadline = trim((string) ($setting->hero_subheadline ?? ''));
    if ($subheadline === '') {
        $subheadline = __('site.hero.subtitle');
    }
@endphp

<section class="lux-hero" aria-label="Abahizi Rwanda">
    <div class="lux-hero__media">
        @if($videoUrl !== '')
            <video class="lux-hero__video" autoplay muted loop playsinline poster="{{ $posterUrl }}" preload="metadata">
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        @else
            <div class="lux-hero__fallback" style="background-image: url('{{ $posterUrl }}');" role="img" aria-label="Masoro factory craftsmanship"></div>
        @endif
        <div class="lux-hero__overlay"></div>
    </div>

    <div class="container lux-hero__content">
        <p class="lux-hero__eyebrow wow tpfadeUp" data-wow-duration=".8s">Masoro, Rwanda · B-Corp Certified</p>
        <h1 class="lux-hero__title wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">{{ $headline }}</h1>
        <p class="lux-hero__subtitle wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">{{ $subheadline }}</p>
        <div class="lux-hero__actions wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".3s">
            <a href="{{ route('manufacturing') }}" class="tp-btn lux-hero__btn lux-hero__btn--primary">{{ __('site.hero.cta_primary') }}</a>
            <a href="{{ route('impactPage') }}" class="lux-hero__btn lux-hero__btn--ghost">{{ __('site.hero.cta_secondary') }}</a>
        </div>
    </div>
</section>
