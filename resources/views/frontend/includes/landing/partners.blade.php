@php
    $partnerList = collect($partners ?? [])->take(4);
    $fallbackPartners = [
        ['names' => 'Kate Spade', 'image' => null],
        ['names' => 'Coach', 'image' => null],
        ['names' => 'B Corp', 'image' => null],
        ['names' => 'NextGen Partners', 'image' => null],
    ];

    if ($partnerList->isEmpty()) {
        $partnerList = collect($fallbackPartners)->map(fn ($p) => (object) $p);
    }

    $facilityImage = null;
    if (! empty($about->factory_services_image ?? null)) {
        $facilityImage = asset('storage/images/' . ltrim($about->factory_services_image, '/'));
    } elseif (isset($homeGallery) && $homeGallery->count() > 1 && ! empty($homeGallery->skip(1)->first()->image)) {
        $facilityImage = asset('storage/images/gallery/' . $homeGallery->skip(1)->first()->image);
    } else {
        $facilityImage = asset('assets/img/slider/slider-bg-3-3.jpg');
    }
@endphp

<section class="lh-partners" id="lh-partners" aria-labelledby="lh-partners-title">
    <div class="container">
        <div class="lh-partners__layout lh-reveal">
            <div>
                <p class="lh-eyebrow">{{ __('site.landing.partners_eyebrow') }}</p>
                <h2 id="lh-partners-title" class="lh-partners__title">{{ __('site.landing.partners_title') }}</h2>
                <div class="lh-partners__logos">
                    @foreach($partnerList as $partner)
                        @php
                            $logoPath = $partner->image ?? '';
                            $logoUrl = $logoPath !== ''
                                ? asset('storage/images/partners' . (str_starts_with($logoPath, '/') ? '' : '/') . ltrim($logoPath, '/'))
                                : null;
                            $name = $partner->names ?? $partner->name ?? 'Partner';
                        @endphp
                        <div class="lh-partners__logo">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $name }}" loading="lazy" decoding="async">
                            @else
                                <span class="lh-partners__wordmark">{{ $name }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="lh-partners__media">
                <img src="{{ $facilityImage }}" alt="{{ __('site.landing.partners_media_alt') }}" loading="lazy" decoding="async">
            </div>
        </div>
    </div>
</section>
