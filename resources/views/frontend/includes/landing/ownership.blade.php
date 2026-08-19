@php
    $ownershipImage = null;
    if (! empty($about->factory_services_image ?? null)) {
        $ownershipImage = asset('storage/images/' . ltrim($about->factory_services_image, '/'));
    } elseif (isset($homeGallery) && $homeGallery->isNotEmpty() && ! empty($homeGallery->first()->image)) {
        $ownershipImage = asset('storage/images/gallery/' . $homeGallery->first()->image);
    } else {
        $ownershipImage = asset('assets/img/slider/slider-bg-3-2.jpg');
    }

    $points = [
        __('site.landing.own_point_1'),
        __('site.landing.own_point_2'),
        __('site.landing.own_point_3'),
    ];
@endphp

<section class="lh-own" id="lh-ownership" aria-labelledby="lh-own-title">
    <div class="container">
        <div class="lh-own__grid">
            <div class="lh-own__media lh-reveal">
                <img src="{{ $ownershipImage }}" alt="{{ __('site.landing.own_media_alt') }}" loading="lazy" decoding="async">
            </div>
            <div class="lh-reveal">
                <p class="lh-eyebrow">{{ __('site.landing.own_eyebrow') }}</p>
                <h2 id="lh-own-title" class="lh-own__title">{{ __('site.landing.own_title') }}</h2>
                <p class="lh-own__text lh-body">{{ __('site.landing.own_text') }}</p>
                <ul class="lh-own__list">
                    @foreach($points as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('impactEmployeeEmpowerment') }}" class="lh-btn lh-btn--ghost-dark">{{ __('site.landing.own_cta') }}</a>
            </div>
        </div>
    </div>
</section>
