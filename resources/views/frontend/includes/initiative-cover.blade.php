@php
    $ways = $activity->normalizedInvolvementWays();
    $channelsReady = $formChannels['channels_ready'] ?? false;
    $oldWay = old('involvement_slug');
    $coverUrl = !empty($activity->image)
        ? asset('storage/' . ltrim($activity->image, '/'))
        : '';
@endphp

<section class="initiative-cover{{ $coverUrl === '' ? ' initiative-cover--plain' : '' }}" aria-label="{{ $activity->title }}">
    @if($coverUrl !== '')
        <div class="initiative-cover__media" aria-hidden="true">
            <img src="{{ $coverUrl }}" alt="">
        </div>
    @endif
    <div class="initiative-cover__overlay">
        <div class="container">
            <div class="initiative-cover__bar">
                <h1 class="initiative-cover__title">{{ $activity->title }}</h1>
                @if(count($ways) > 0)
                    <button
                        type="button"
                        class="tp-btn tp-btn--lux initiative-cover__cta"
                        data-bs-toggle="modal"
                        data-bs-target="#getInvolvedModal"
                    >
                        {{ __('site.initiative.cta_jump') }} <span aria-hidden="true">→</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>
