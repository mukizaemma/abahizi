@php
    $values = [
        ['icon' => 'fa-hands-holding', 'title' => \App\Support\SiteCopy::get('value_1_title'), 'text' => \App\Support\SiteCopy::get('value_1_text')],
        ['icon' => 'fa-people-group', 'title' => \App\Support\SiteCopy::get('value_2_title'), 'text' => \App\Support\SiteCopy::get('value_2_text')],
        ['icon' => 'fa-seedling', 'title' => \App\Support\SiteCopy::get('value_3_title'), 'text' => \App\Support\SiteCopy::get('value_3_text')],
    ];
@endphp

<section class="lh-move" id="lh-values" aria-labelledby="lh-move-title">
    <div class="container">
        <div class="lh-move__grid">
            <div class="lh-move__copy lh-reveal">
                <span class="lh-move__mark" aria-hidden="true">
                    <i class="fas fa-dharmachakra"></i>
                </span>
                <p class="lh-eyebrow">{{ \App\Support\SiteCopy::get('move_eyebrow') }}</p>
                <h2 id="lh-move-title" class="lh-move__title">{{ \App\Support\SiteCopy::get('move_title') }}</h2>
                <p class="lh-move__text lh-body">{{ \App\Support\SiteCopy::get('move_text') }}</p>
                <a href="{{ route('contacts') }}" class="lh-btn lh-btn--solid">{{ \App\Support\SiteCopy::get('move_cta') }}</a>
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
