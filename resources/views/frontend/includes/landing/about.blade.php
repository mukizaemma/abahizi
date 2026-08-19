@php
    $plainText = static function (?string $html): string {
        $text = html_entity_decode((string) ($html ?? ''));
        $text = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $text);
        $text = preg_replace('/<\s*\/p\s*>/i', "\n", $text);
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    };

    $aboutBody = $plainText($about->solution_statement ?? $about->what_we_do ?? '');
    $aboutBody = str_replace('Abahizi Rwanda', 'Abahizi CBC', $aboutBody);
    if ($aboutBody === '') {
        $aboutBody = __('site.landing.about_text');
    } else {
        $aboutBody = \Illuminate\Support\Str::limit($aboutBody, 420, '…');
    }

    $aboutImage = \App\Support\SectionBackgroundService::craftFeatureImage($about ?? null);
    if ($aboutImage === null) {
        $aboutImage = asset('assets/img/slider/slider-bg-3-2.jpg');
    }

    $storyVideo = trim((string) ($setting->youtube ?? $setting->hero_video_url ?? ''));
    $hasStoryVideo = $storyVideo !== '';
@endphp

<section class="lh-about" id="lh-about" aria-labelledby="lh-about-title">
    <div class="container">
        <div class="lh-about__grid">
            <div class="lh-reveal">
                <h2 id="lh-about-title" class="lh-about__title">{{ __('site.landing.about_title') }}</h2>
                <p class="lh-about__text lh-body">{{ $aboutBody }}</p>
                <a href="{{ route('backgroundDetails') }}" class="lh-btn lh-btn--ghost-dark">{{ __('site.landing.about_cta') }}</a>
            </div>
            <div class="lh-reveal">
                <div class="lh-about__media">
                    <img src="{{ $aboutImage }}" alt="{{ __('site.landing.about_media_alt') }}" loading="lazy" decoding="async">
                    @if($hasStoryVideo)
                        <button
                            type="button"
                            class="lh-about__play"
                            data-lh-video-open
                            data-lh-video-src="{{ $storyVideo }}"
                            aria-label="{{ __('site.landing.watch_story') }}"
                        >
                            <span class="lh-about__play-icon" aria-hidden="true"><i class="fas fa-play"></i></span>
                            <span class="lh-about__watch">{{ __('site.landing.watch_story') }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
