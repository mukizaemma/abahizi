@php
    $steps = [
        ['title' => __('site.landing.process_1'), 'desc' => __('site.landing.process_1_desc')],
        ['title' => __('site.landing.process_2'), 'desc' => __('site.landing.process_2_desc')],
        ['title' => __('site.landing.process_3'), 'desc' => __('site.landing.process_3_desc')],
        ['title' => __('site.landing.process_4'), 'desc' => __('site.landing.process_4_desc')],
        ['title' => __('site.landing.process_5'), 'desc' => __('site.landing.process_5_desc')],
        ['title' => __('site.landing.process_6'), 'desc' => __('site.landing.process_6_desc')],
    ];
@endphp

<section class="lh-process" id="lh-process" aria-labelledby="lh-process-title">
    <div class="container">
        <div class="lh-process__intro lh-reveal">
            <div>
                <p class="lh-eyebrow">{{ __('site.landing.process_eyebrow') }}</p>
                <h2 id="lh-process-title" class="lh-process__title">{{ __('site.landing.process_title') }}</h2>
            </div>
            <p class="lh-process__lead lh-body">{{ __('site.landing.process_lead') }}</p>
        </div>

        <ol class="lh-process__track lh-reveal">
            @foreach($steps as $index => $step)
                <li class="lh-process__step">
                    <div class="lh-process__marker" aria-hidden="true">
                        <span class="lh-process__dot">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="lh-process__copy">
                        <h3 class="lh-process__step-title">{{ $step['title'] }}</h3>
                        <p class="lh-process__step-desc">{{ $step['desc'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>

        <div class="lh-process__foot lh-reveal">
            <a href="{{ route('ourFactory') }}" class="lh-btn lh-btn--ghost-dark">{{ __('site.landing.process_cta') }}</a>
        </div>
    </div>
</section>
