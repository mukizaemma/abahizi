@php
    use App\Models\Slide;

    $brandName = trim((string) ($setting->company ?? 'Abahizi CBC'));
    $headline = trim((string) ($setting->hero_headline ?? ''));
    if ($headline === '') {
        $headline = __('site.landing.hero_title');
    }

    $subtitle = trim((string) ($setting->hero_subheadline ?? ''));
    if ($subtitle === '') {
        $subtitle = __('site.landing.hero_subtitle');
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

    $headlineParts = preg_split('/(?<=[.!?])\s+/', $headline) ?: [$headline];
    $headlineParts = array_values(array_filter(array_map('trim', $headlineParts), fn ($part) => $part !== ''));
    if ($headlineParts === []) {
        $headlineParts = [$headline];
    }

    $barItems = [
        ['icon' => 'fa-users', 'label' => \App\Support\SiteCopy::get('bar_1')],
        ['icon' => 'fa-hand-holding-heart', 'label' => \App\Support\SiteCopy::get('bar_2')],
        ['icon' => 'fa-heart', 'label' => \App\Support\SiteCopy::get('bar_3')],
        ['icon' => 'fa-location-dot', 'label' => \App\Support\SiteCopy::get('bar_4')],
    ];
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
                <source src="{{ $videoUrl }}" type="mp4">
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

    <div class="container lh-hero__content">
        <div class="lh-hero__copy">
            <h1 class="lh-hero__title">
                @foreach($headlineParts as $index => $part)
                    @php
                        $isAccent = count($headlineParts) > 1 && $index === 1;
                    @endphp
                    <span class="lh-hero__title-line{{ $isAccent ? ' lh-hero__title-line--accent' : '' }}">{{ $part }}</span>
                @endforeach
            </h1>
            <p class="lh-hero__subtitle">{{ $subtitle }}</p>
            <div class="lh-hero__actions">
                <a href="#lh-about" class="lh-btn lh-btn--solid">{{ \App\Support\SiteCopy::get('cta_story') }}</a>
                <a href="#lh-impact" class="lh-btn lh-btn--ghost">
                    <i class="far fa-heart" aria-hidden="true"></i>
                    {{ \App\Support\SiteCopy::get('cta_impact') }}
                </a>
            </div>
        </div>

        <div class="lh-hero__seal" aria-hidden="true">
            <svg viewBox="0 0 200 200" class="lh-hero__seal-svg">
                <defs>
                    <path id="lh-seal-circle" d="M100,100 m-72,0 a72,72 0 1,1 144,0 a72,72 0 1,1 -144,0" />
                </defs>
                <circle cx="100" cy="100" r="92" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.35" />
                <text class="lh-hero__seal-text">
                    <textPath href="#lh-seal-circle" startOffset="0%">{{ \App\Support\SiteCopy::get('hero_seal') }} · {{ \App\Support\SiteCopy::get('hero_seal') }}</textPath>
                </text>
            </svg>
            <span class="lh-hero__seal-mark"><i class="fas fa-certificate"></i></span>
        </div>
    </div>

    <div class="lh-hero__bar" aria-label="{{ $brandName }} highlights">
        <div class="container">
            <ul class="lh-hero__bar-list">
                @foreach($barItems as $item)
                    <li>
                        <span class="lh-hero__bar-icon" aria-hidden="true"><i class="fas {{ $item['icon'] }}"></i></span>
                        <span>{{ $item['label'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
