@extends('layouts.frontbase')


@section('content')

    <!-- service-area-start -->

    
    <div class="tp-donate__area p-relative fix">
        <div class="tp-donate__shape-3 d-none d-lg-block">
            <img src="assets/img/donate/donate-shape-1-4.png" alt="">
        </div>
        <div class="tp-donate__bg">
            <div class="container">
                <div class="row align-items-center">
                    <div style="padding: 30px; background: #f8f8f8; border-radius: 10px; margin-bottom: 40px; text-align: center;">
                    <h2 style="color: #444; font-size: 28px; margin-bottom: 15px;">💛 Why Your Support Matters</h2>
                    <p style="font-size: 16px; color: #333; line-height: 1.7; margin-bottom: 20px;">
                        Teenage pregnancy has left many girls in our communities raising children alone, without skills or support. 
                        Each program under <strong>Abahizi Rwanda-</strong> equips single teen mothers with practical skills — from tailoring to childcare and counseling — helping them regain confidence, earn a sustainable income, and raise healthy children.
                    </p>
                    <p style="font-size: 16px; color: #333; line-height: 1.7; margin-bottom: 20px;">
                        <strong>Your donation or partnership helps provide:</strong> quality training, tools, materials, and psychosocial support. 
                        Every contribution makes a difference — no amount is too small to transform a life.
                    </p>
                    <p style="font-size: 16px; color: #333; line-height: 1.7; margin-bottom: 20px;">
                        🟡 <strong>Click “Donate Now”</strong> on our website to give instantly.<br>
                        🔵 Want to sponsor a full course, partner with us, or request a tax-exempt donation receipt? 
                        <strong>Reach out through our contact page</strong> or connect with a fundraiser listed in the <em>Team</em> section.
                    </p>
                    <p style="font-size: 16px; color: #444; font-weight: bold; margin-top: 30px;">
                        👉 Explore the available causes below and select the one that touches your heart — or get in touch to join us in a more structured way.
                    </p>
                    </div>

                    <div class="col-xl-8 col-lg-8 col-md-8">
                        
                        <div class="tp-donate__section-title">
                            <h4 class="tp-section-title">Our Causes</h4>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <div class="tp-donate__section-arrow d-flex justify-content-start  justify-content-md-end pb-50">
                            <div class="test-next">
                                <button><i class="far fa-arrow-left"></i></button>
                            </div>
                            <div class="test-prev">
                                <button><i class="far fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-container tp-donate__active">


                    <div class="swiper-wrapper">
                        @foreach ($campaigns as $campaign)
                        <div class="swiper-slide">
                            <div class="tp-donate__item">
                                <div class="tp-donate__thumb p-relative fix">
                                    <img src="{{ asset('storage/images/campaigns/' . $campaign->image) }}" alt="">
                                    <div class="tp-donate__thumb-shape">
                                        <img src="{{ asset('storage/images/campaigns/' . $campaign->image) }}" alt="">
                                        {{-- <div class="tp-donate__thumb-text">
                                            <span>SHELTER</span>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="tp-donate__content">
                                    <div class="tp-donate__text">
                                        <a href="{{ route('campaign',['slug'=>$campaign->slug]) }}">
                                            <h5 class="tp-donate__title">{{ $campaign->title }}
                                            </h5>
                                        </a>
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 100) }}</p>
                                    </div>
                                    <div class="tp-donate-progress">
                                        <div class="tp-donate-progress-item fix">
                                            <span class="progress-count">{{ $campaign->percentage }}%</span>
                                            <div class="progress">
                                                <div class="progress-bar wow slideInLeft" data-wow-duration="1s"
                                                    data-wow-delay=".3s" role="progressbar" data-width="42%"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 58%; visibility: visible; animation-duration: 1s; animation-delay: 0.3s; animation-name: slideInLeft;">
                                                </div>
                                            </div>
                                            <div class="progress-goals">
                                                <span>Raised <b> ${{ $campaign->raised }}</b></span>
                                                <span>Goal <b> ${{ $campaign->goal }}</b></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tp-donate__button">
                                        <a class="tp-grey-btn" href="{{ route('campaigns') }}">Donate Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>                            
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- service-area-end -->

        <!-- cta-area-start -->
            @include('frontend.includes.backImage')
        <!-- cta-area-end -->


@endsection
