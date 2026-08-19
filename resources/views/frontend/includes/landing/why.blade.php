@php
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
        <div class="lh-why__head lh-reveal">
            <div>
                <h2 id="lh-why-title" class="lh-why__title">{{ __('site.landing.why_title') }}</h2>
            </div>
            <p class="lh-why__lead lh-body">{{ __('site.landing.why_lead') }}</p>
        </div>

        <div class="lh-why__grid lh-reveal">
            @foreach($pillars as $index => $pillar)
                <article class="lh-why__item">
                    <span class="lh-why__num" aria-hidden="true">{{ $pillar['num'] }}</span>
                    <h3 class="lh-why__item-title">{{ $pillar['title'] }}</h3>
                    <p class="lh-why__item-text lh-body">{{ $pillar['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
