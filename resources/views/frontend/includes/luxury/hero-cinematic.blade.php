@php
    use App\Models\Slide;

    $defaultCaption = trim((string) ($setting->hero_headline ?? ''));
    if ($defaultCaption === '') {
        $defaultCaption = __('site.hero.title');
    }

    $videoUrl = trim((string) ($setting->hero_video_url ?? ''));
    if ($videoUrl !== '' && ! str_starts_with($videoUrl, 'http')) {
        $videoUrl = asset('storage/videos/' . ltrim($videoUrl, '/'));
    }

    $posterFromSetting = ! empty($setting->hero_poster ?? null)
        ? asset('storage/images/' . ltrim($setting->hero_poster, '/'))
        : null;

    $heroSlides = collect($slides ?? [])
        ->filter(fn ($slide) => ! empty($slide->image))
        ->values();

    if ($heroSlides->isEmpty()) {
        $fallbackUrl = $posterFromSetting ?? asset('assets/img/slider/slider-3-1.jpg');
        $heroSlides = collect([
            (object) [
                'image' => null,
                'heading' => $defaultCaption,
                'url' => $fallbackUrl,
            ],
        ]);
    }

    $useVideo = $videoUrl !== '' && collect($slides ?? [])->filter(fn ($s) => ! empty($s->image))->isEmpty();
    $firstCaption = trim((string) ($heroSlides->first()->heading ?? ''));
    $initialCaption = $firstCaption !== '' ? $firstCaption : $defaultCaption;
@endphp

<section
    class="lux-hero lux-hero--minimal lux-hero--slides"
    aria-label="Abahizi Rwanda"
    @if(! $useVideo && $heroSlides->count() > 1)
        data-lux-hero-slides
        data-lux-hero-interval="9000"
    @endif
>
    <div class="lux-hero__media">
        @if($useVideo)
            <video class="lux-hero__video" autoplay muted loop playsinline poster="{{ $posterFromSetting ?? Slide::publicImageUrl($heroSlides->first()->image ?? '') }}" preload="metadata">
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        @else
            @foreach($heroSlides as $index => $slide)
                @php
                    $imageUrl = $slide->url ?? Slide::publicImageUrl($slide->image);
                    $slideCaption = trim((string) ($slide->heading ?? ''));
                    if ($slideCaption === '') {
                        $slideCaption = $defaultCaption;
                    }
                    $zoomClass = $index % 2 === 0 ? 'lux-hero__kenburns--in' : 'lux-hero__kenburns--out';
                @endphp
                <div
                    class="lux-hero__slide{{ $index === 0 ? ' is-active' : '' }}"
                    data-lux-hero-slide
                    data-caption="{{ e($slideCaption) }}"
                >
                    <div
                        class="lux-hero__kenburns {{ $zoomClass }}{{ $index === 0 ? ' is-animating' : '' }}"
                        style="background-image: url('{{ $imageUrl }}');"
                        role="img"
                        aria-hidden="true"
                    ></div>
                </div>
            @endforeach
        @endif
        <div class="lux-hero__overlay"></div>
    </div>

    <div class="container lux-hero__content lux-hero__content--minimal">
        <h1 class="lux-hero__title lux-hero__title--solo" data-lux-hero-caption data-fallback-caption="{{ e($defaultCaption) }}">{{ $initialCaption }}</h1>
        <div class="lux-hero__actions">
            <a href="{{ route('contacts') }}" class="tp-btn tp-btn--lux lux-hero__btn lux-hero__btn--primary">{{ __('site.nav.inquiry') }}</a>
            <a href="{{ route('ourFactory') }}" class="lux-hero__btn lux-hero__btn--ghost">{{ __('site.hero.cta_secondary') }}</a>
        </div>
    </div>
</section>
