@php
    $values = [
        ['icon' => 'fa-hands-holding', 'title' => __('site.landing.value_1_title'), 'text' => __('site.landing.value_1_text')],
        ['icon' => 'fa-people-group', 'title' => __('site.landing.value_2_title'), 'text' => __('site.landing.value_2_text')],
        ['icon' => 'fa-seedling', 'title' => __('site.landing.value_3_title'), 'text' => __('site.landing.value_3_text')],
    ];
@endphp

<section class="lh-move" id="lh-values" aria-labelledby="lh-move-title">
    <div class="container">
        <div class="lh-move__grid">
            <div class="lh-move__copy lh-reveal">
                <span class="lh-move__mark" aria-hidden="true">
                    <i class="fas fa-dharmachakra"></i>
                </span>
                <p class="lh-eyebrow">{{ __('site.landing.move_eyebrow') }}</p>
                <h2 id="lh-move-title" class="lh-move__title">{{ __('site.landing.move_title') }}</h2>
                <p class="lh-move__text lh-body">{{ __('site.landing.move_text') }}</p>
                <a href="{{ route('contacts') }}" class="lh-btn lh-btn--solid">{{ __('site.landing.move_cta') }}</a>
            </div>
            <div class="lh-move__cards">
                @foreach($values as $index => $value)
                    <article class="lh-value-card lh-reveal" style="transition-delay: {{ $index * 0.08 }}s">
                        <span class="lh-value-card__icon" aria-hidden="true"><i class="fas {{ $value['icon'] }}"></i></span>
                        <h3>{{ $value['title'] }}</h3>
                        <p>{{ $value['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
