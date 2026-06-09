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

    $caption = trim((string) ($setting->hero_subheadline ?? ''));
    if ($caption === '') {
        $caption = __('site.hero.subtitle');
    }
@endphp

<section class="lux-hero lux-hero--minimal" aria-label="Abahizi Rwanda">
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

    <div class="container lux-hero__content lux-hero__content--minimal">
        <p class="lux-hero__caption wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">{{ $caption }}</p>
        <div class="lux-hero__actions wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
            <a href="{{ route('contacts') }}" class="tp-btn lux-hero__btn lux-hero__btn--primary">{{ __('site.hero.cta_primary') }}</a>
            <a href="{{ route('ourFactory') }}" class="lux-hero__btn lux-hero__btn--ghost">{{ __('site.hero.cta_secondary') }}</a>
        </div>
    </div>
</section>
