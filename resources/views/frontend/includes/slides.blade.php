        <div class="tp-slider-3__area p-relative custom-banners">
            {{-- <div class="tp-slider-3__icon smooth">
                <a href="#service" class="arrow-scroll-btn"><i class="flaticon-down-arrow"></i></a>
            </div> --}}

            <div class="tp-slider-3__wrapper">
                <div class="swiper-container tp-slider-3__active">
                    <div class="swiper-wrapper">
                        @foreach ($slides as $slide)
                        <div class="swiper-slide">
                            <div class="tp-slider-3__bg z-index fix p-relative" data-background="{{ \App\Models\Slide::publicImageUrl($slide->image) }}">
                                <div class="tp-slider-3__shape">
                                    <img src="assets/img/slider/slider-shape-3-1.png" alt="">
                                </div>
                                <div class="tp-slider-3__text">
                                    <h3 class="tp-slider-3__big-text"></h3>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="tp-slider-3__content text-center">
                                                <h2 class="tp-slider-3-title pb-30">{{ $slide->heading }}</h2>
                                                <a class="tp-btn theme-1-bg" href="{{ route('backgroundDetails') }}">Discover More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>