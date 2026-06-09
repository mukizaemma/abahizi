@php
    $journeyProfiles = ($testimonials ?? collect())->take(4);
    $defaultMilestones = [
        'Vocational training at Masoro',
        'Financial literacy & skills building',
        'Stable employment with full benefits',
        'Path to employee ownership',
    ];
@endphp

<section class="lux-journey" aria-labelledby="lux-journey-heading">
    <div class="container">
        <div class="lux-section-head lux-section-head--solo mb-4">
            <h2 id="lux-journey-heading" class="lux-section-head__title mb-0">{{ __('site.impact.artisan_journey') }}</h2>
        </div>

        @if($journeyProfiles->isEmpty())
            <article class="lux-journey__card lux-journey__card--demo">
                <div class="lux-journey__media">
                    <div class="lux-journey__placeholder" aria-hidden="true"></div>
                </div>
                <div class="lux-journey__content">
                    <h3 class="lux-journey__name">Factory employee leadership story</h3>
                    <p class="lux-journey__role text-muted">From vocational training to employee-owner</p>
                    <ol class="lux-journey__milestones">
                        @foreach($defaultMilestones as $milestone)
                            <li>{{ $milestone }}</li>
                        @endforeach
                    </ol>
                </div>
            </article>
        @else
            <div class="lux-journey__slider" data-lux-journey-slider>
                @foreach($journeyProfiles as $profile)
                    @php
                        $coverUrl = ! empty($profile->image)
                            ? asset('storage/' . ltrim($profile->image, '/'))
                            : (! empty($profile->image) ? asset('storage/images/testimonies/' . ltrim($profile->image, '/')) : null);
                        $excerpt = \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($profile->testimony ?? '')), 160, '…');
                    @endphp
                    <article class="lux-journey__card">
                        <div class="lux-journey__media">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="{{ $profile->names }}" class="lux-journey__img" loading="lazy" decoding="async">
                            @else
                                <div class="lux-journey__placeholder" aria-hidden="true"></div>
                            @endif
                        </div>
                        <div class="lux-journey__content">
                            <h3 class="lux-journey__name">{{ $profile->names }}</h3>
                            @if(!empty($profile->title))
                                <p class="lux-journey__role text-muted">{{ $profile->title }}</p>
                            @endif
                            @if($excerpt !== '')
                                <p class="lux-journey__quote">“{{ $excerpt }}”</p>
                            @endif
                            <ol class="lux-journey__milestones">
                                @foreach($defaultMilestones as $milestone)
                                    <li>{{ $milestone }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </article>
                @endforeach
            </div>
            @if($journeyProfiles->count() > 1)
                <div class="lux-journey__nav">
                    <button type="button" class="lux-journey__arrow" data-lux-journey-prev aria-label="Previous story">&larr;</button>
                    <button type="button" class="lux-journey__arrow" data-lux-journey-next aria-label="Next story">&rarr;</button>
                </div>
            @endif
        @endif
    </div>
</section>
