@php
    use App\Support\SectionBackgroundService;

    $whyImage = SectionBackgroundService::whyFeatureImage($about ?? null);

    $pillars = [
        [
            'num' => '01',
            'title' => __('site.landing.why_1_title'),
            'text' => __('site.landing.why_1_text'),
        ],
        [
            'num' => '02',
            'title' => __('site.landing.why_2_title'),
            'text' => __('site.landing.why_2_text'),
        ],
        [
            'num' => '03',
            'title' => __('site.landing.why_3_title'),
            'text' => __('site.landing.why_3_text'),
        ],
    ];
@endphp

<section class="lh-why" id="lh-why" aria-labelledby="lh-why-title">
    <div class="container">
        <div class="lh-why__layout{{ $whyImage ? '' : ' lh-why__layout--solo' }}">
            @if($whyImage)
                <figure class="lh-why__media lh-reveal">
                    <img src="{{ $whyImage }}" alt="{{ __('site.landing.why_media_alt') }}" loading="lazy" decoding="async">
                    <figcaption class="lh-why__caption">{{ __('site.landing.why_caption') }}</figcaption>
                </figure>
            @endif

            <div class="lh-why__copy lh-reveal">
                <h2 id="lh-why-title" class="lh-why__title">{{ __('site.landing.why_title') }}</h2>
                <p class="lh-why__lead lh-body">{{ __('site.landing.why_lead') }}</p>

                <div class="lh-why__list">
                    @foreach($pillars as $pillar)
                        <article class="lh-why__item">
                            <span class="lh-why__num" aria-hidden="true">{{ $pillar['num'] }}</span>
                            <div>
                                <h3 class="lh-why__item-title">{{ $pillar['title'] }}</h3>
                                <p class="lh-why__item-text lh-body">{{ $pillar['text'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <a href="{{ route('ourFactory') }}" class="lh-btn lh-btn--ghost-dark lh-why__cta">{{ __('site.landing.why_cta') }}</a>
            </div>
        </div>
    </div>
</section>
