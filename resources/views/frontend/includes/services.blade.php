@php
    $whatWeDoIntro = trim(strip_tags(html_entity_decode($about->what_we_do ?? '')));
    if ($whatWeDoIntro === '') {
        $whatWeDoIntro = 'We manufacture premium handbags and accessories for global markets while running community empowerment programs that create lasting opportunity for women and families across Rwanda.';
    } else {
        $whatWeDoIntro = \Illuminate\Support\Str::limit($whatWeDoIntro, 360, '…');
    }

    $splitImage = null;
    if (!empty($about->factory_services_image)) {
        $splitImage = asset('storage/images/' . $about->factory_services_image);
    } elseif (isset($homeGallery) && $homeGallery->isNotEmpty() && !empty($homeGallery->first()->image)) {
        $splitImage = asset('storage/images/gallery/' . $homeGallery->first()->image);
    } else {
        $splitImage = asset('assets/img/breadcrumb/breadcrumb-bg-1.jpg');
    }
@endphp

<section class="home-programs-split" aria-labelledby="home-programs-split-title">
    <div class="container-fluid px-0">
        <div class="row g-0 align-items-stretch home-programs-split__row">
            <div class="col-lg-5 col-xl-5 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                <div class="home-programs-split__intro h-100">
                    <div class="home-programs-split__intro-body">
                        <h2 id="home-programs-split-title" class="home-programs-split__title">{{ __('site.nav.what_we_do') }}</h2>
                        <p class="home-programs-split__lead">{{ $whatWeDoIntro }}</p>
                        <div class="home-programs-split__actions d-flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('whatWeDo') }}" class="tp-btn">{{ __('site.nav.what_we_do') }} <span aria-hidden="true">→</span></a>
                            <a href="{{ route('ourFactory') }}" class="home-programs-split__link-btn">{{ __('site.nav.factory') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-xl-7 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                <a
                    href="{{ route('ourFactory') }}"
                    class="home-programs-split__feature h-100 d-block"
                    style="background-image: url('{{ $splitImage }}');"
                >
                    <div class="home-programs-split__feature-footer">
                        <h3 class="home-programs-split__feature-title">{{ __('site.nav.factory') }}</h3>
                        <span class="home-programs-split__feature-btn-wrap">
                            <span class="tp-btn home-programs-split__feature-btn">{{ __('site.hero.cta_secondary') }} <span>→</span></span>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
