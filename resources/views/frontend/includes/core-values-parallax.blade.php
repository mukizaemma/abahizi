@php
    $cvBgFile = $about->core_values_background ?? $about->image2 ?? $about->image1 ?? $about->image ?? '';
    $parallaxUrl = $cvBgFile !== ''
        ? (str_starts_with((string) $cvBgFile, 'http')
            ? $cvBgFile
            : asset('storage/images/' . ltrim($cvBgFile, '/')))
        : '';
    $coreValueItems = \App\Support\CoreValues::parseItems($mission->core_values_list ?? null, $mission->values ?? '');

    $sectionClassExtra = $coreValuesParallaxSectionClass ?? '';
    $overlayClassExtra = $coreValuesParallaxOverlayClass ?? '';
    $cvTitle = $coreValuesParallaxTitle ?? 'Our Core Values';
    $cvSubtitle = $coreValuesParallaxSubtitle ?? null;
    $showCvCta = $showCoreValuesGetInvolvedCta ?? false;
@endphp

<section class="about-core-values-parallax {{ $parallaxUrl ? '' : 'about-core-values-parallax--no-bg' }} {{ $sectionClassExtra }}"
    @if($parallaxUrl)
        style="--about-parallax-image: url('{{ $parallaxUrl }}');"
    @endif
>
    <div class="about-core-values-parallax__overlay {{ $overlayClassExtra }}">
        <div class="container py-2 py-lg-3">
            <div class="row justify-content-center mb-4 mb-lg-5">
                <div class="col-12 text-center">
                    <h3 class="about-core-values-parallax__title mb-0">{{ $cvTitle }}</h3>
                    @if($cvSubtitle)
                        <p class="text-white-50 small mt-2 mb-0 mx-auto" style="max-width: 32rem;">{{ $cvSubtitle }}</p>
                    @endif
                </div>
            </div>

            @if(count($coreValueItems) > 0)
                <ul class="row g-3 g-md-4 list-unstyled mb-0 justify-content-center core-values-grid">
                    @foreach($coreValueItems as $idx => $item)
                        <li class="col-sm-6 col-lg-4">
                            <div class="core-values-card h-100">
                                <span class="core-values-card__index" aria-hidden="true">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <p class="core-values-card__text mb-0">{{ $item }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8 text-center text-white">
                        <div class="about-core-values-parallax__body postbox__text mx-auto" style="font-size: 18px; line-height: 1.8;">
                            {!! $mission->values ?? '' !!}
                        </div>
                    </div>
                </div>
            @endif

            @if($showCvCta)
                <div class="home-core-values-cta-wrap text-center">
                    <a href="{{ route('getInvolved') }}" class="tp-btn home-core-values-cta-btn">Get involved</a>
                </div>
            @endif
        </div>
    </div>
</section>
