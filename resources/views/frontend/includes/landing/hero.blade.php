@php
    use App\Models\Slide;

    $brandName = trim((string) ($setting->company ?? 'Abahizi CBC'));
    $headline = trim((string) ($setting->hero_headline ?? ''));
    if ($headline === '') {
        $headline = __('site.landing.hero_title');
    }

    $subheadline = trim((string) ($setting->hero_subheadline ?? ''));
    if ($subheadline === '') {
        $subheadline = __('site.landing.hero_subtitle');
    }

    $heroType = $setting->resolvedHeroMediaType();
    $videoUrl = $setting->heroVideoPublicUrl();
    $posterFromSetting = $setting->heroPosterPublicUrl();

    $heroSlides = collect($slides ?? [])
        ->filter(fn ($slide) => ! empty($slide->image))
        ->values();

    $fallbackUrl = $posterFromSetting ?? asset('assets/img/slider/slider-bg-3-1.jpg');
    if ($heroSlides->isEmpty()) {
        $heroSlides = collect([
            (object) [
                'image' => null,
                'url' => $fallbackUrl,
            ],
        ]);
    }

    $useVideo = $heroType === 'video' && $videoUrl;
    $useSingleImage = $heroType === 'image' || ($heroType === 'video' && ! $useVideo);
    $useSlideshow = ! $useVideo && ! $useSingleImage;

    if ($useSingleImage) {
        $bannerUrl = $posterFromSetting;
        if (! $bannerUrl) {
            $first = $heroSlides->first();
            $bannerUrl = $first->url ?? Slide::publicImageUrl($first->image ?? '');
        }
        if (! $bannerUrl) {
            $bannerUrl = $fallbackUrl;
        }
        $heroSlides = collect([
            (object) [
                'image' => null,
                'url' => $bannerUrl,
            ],
        ]);
    }

    $youtubeId = null;
    if ($useVideo && $videoUrl) {
        if (preg_match('#(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})#i', $videoUrl, $match)) {
            $youtubeId = $match[1];
        }
    }
@endphp

<section class="lh-hero" aria-label="{{ $brandName }}">
    <div class="lh-hero__media" @if($useSlideshow && $heroSlides->count() > 1) data-lh-hero-slides data-lh-hero-interval="8000" @endif>
        @if($useVideo && $youtubeId)
            <iframe
                class="lh-hero__video"
                src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&controls=0&playsinline=1&rel=0"
                title="{{ $brandName }}"
                allow="autoplay; encrypted-media"
                allowfullscreen
            ></iframe>
        @elseif($useVideo)
            <video class="lh-hero__video" autoplay muted loop playsinline poster="{{ $posterFromSetting }}" preload="metadata">
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        @else
            @foreach($heroSlides as $index => $slide)
                @php
                    $imageUrl = $slide->url ?? Slide::publicImageUrl($slide->image);
                @endphp
                <div
                    class="lh-hero__slide{{ $index === 0 ? ' is-active' : '' }}"
                    data-lh-hero-slide
                    style="background-image: url('{{ $imageUrl }}');"
                    role="img"
                    aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                ></div>
            @endforeach
        @endif
        <div class="lh-hero__overlay" aria-hidden="true"></div>
    </div>

    <div class="container lh-hero__content lh-reveal is-visible">
        <span class="lh-hero__brand">{{ $brandName }}</span>
        <h1 class="lh-hero__title">{{ $headline }}</h1>
        <p class="lh-hero__subtitle">{{ $subheadline }}</p>
        <div class="lh-hero__actions">
            <a href="#lh-contact" class="lh-btn lh-btn--primary">{{ __('site.landing.cta_partner') }}</a>
            <a href="#lh-products" class="lh-btn lh-btn--ghost">{{ __('site.landing.cta_products') }}</a>
        </div>
    </div>

    <a href="#lh-about" class="lh-hero__scroll" aria-label="Scroll to about">
        <span>Scroll</span>
        <span class="lh-hero__scroll-line" aria-hidden="true"></span>
    </a>
</section>
