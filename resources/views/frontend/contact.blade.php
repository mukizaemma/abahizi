@extends('layouts.frontbase')

@section('content')


        <!-- contact-area-start -->
        <div class="tp-contact__area">
            <div class="container">
                <div class="tp-contact__bg">
                    <div class="tp-contact__wrapper d-flex align-items-center justify-content-between">
                        <div class="tp-contact__item d-flex align-items-center">
                            <div class="tp-contact__icon">
                              <span><i class="flaticon-phone"></i></span>
                            </div>
                            <div class="tp-contact__text">
                                <a href="tel:{{ $contact->phone ?? '' }}">{{ $contact->phone ?? '' }}</a>
                                <a href="tel:{{ $contact->phone1 ?? '' }}">{{ $contact->phone2 ?? '' }}</a>
                            </div>
                        </div>                        
                        <div class="tp-contact__item d-flex align-items-center">
                            <div class="tp-contact__icon">
                              <span><i class="flaticon-email"></i></span>
                            </div>
                            <div class="tp-contact__text">
                                <a href="mailto:{{ $contact->email ?? '' }}">{{ $contact->email ?? '' }}</a>
                                {{-- <a href="mailto:infocompany@gmail.com">infocompany@gmail.com</a> --}}
                            </div>
                        </div>
                        <div class="tp-contact__item d-flex align-items-center">
                            <div class="tp-contact__icon">
                              <span><i class="flaticon-location"></i></span>
                            </div>
                            <div class="tp-contact__text">
                                <a href="#">{{ $contact->address ?? '' }} <br> Rwanda</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- contact-area-end -->


        <!-- form-map-area-start -->
        <div class="tp-contact-form__area tp-contact-form__space">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 wow tpfadeLeft" data-wow-duration=".9s"
                    data-wow-delay=".3s">
                        <div class="tp-contact-form__left-box">
                            <span class="tp-contact-form__subtitle">CONTACT FORM</span>
                            <p>If it's easier for you, fill out the form to let us know how you would like to partner with us</p>
                            <div class="tp-contact-form__social-box">
                            @if(!empty($setting->facebook))
                                <a href="{{ $setting->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if(!empty($setting->instagram))
                                <a href="{{ $setting->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if(!empty($setting->youtube))
                                <a href="{{ $setting->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                            @endif
                            </div>
                        </div>
                        <div class="tp-contact-form__form-box mt-4">
                        <form class="form" action="{{ route('sendMessage') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                                <div class="row">
                                    <div class="col-xl-6 mb-30">
                                        <div class="tp-contact-form__input-box">
                                            <input type="text" placeholder="Your Name" name="names">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 mb-30">
                                        <div class="tp-contact-form__input-box">
                                            <input type="email" placeholder="Your Email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 mb-30">
                                        <div class="tp-contact-form__textarea-box">
                                            <textarea placeholder="Write Your Message" name="message"></textarea>
                                        </div>
                                    </div>
                                </div>   
                                <div class="tp-contact-form__button">
                                <button class="tp-btn" type="submit">Send Your Message</button>
                            </div>                             
                            </form>

                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 wow tpfadeRight" data-wow-duration=".9s"
                    data-wow-delay=".7s">
                        <div class="tp-location__info-box h-100">
                            @php
                                $mapEmbedRaw = trim((string) ($setting->google_map_embed_code ?? ''));
                                $mapSrc = '';
                                $mapIframeHtml = '';

                                if ($mapEmbedRaw !== '') {
                                    if (stripos($mapEmbedRaw, '<iframe') !== false) {
                                        $mapIframeHtml = $mapEmbedRaw;
                                    } else {
                                        $mapSrc = $mapEmbedRaw;
                                    }
                                }

                                $defaultMapSrc = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.434670504606!2d30.1565774!3d-1.9806325999999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca75ea871adfd%3A0x807deb18c1a0592f!2sImpact%20Life%20Mission!5e0!3m2!1sen!2srw!4v1755602240867!5m2!1sen!2srw';
                                $resolvedMapSrc = $mapSrc !== '' ? $mapSrc : $defaultMapSrc;
                            @endphp

                            @if($mapIframeHtml !== '')
                                {!! $mapIframeHtml !!}
                            @else
                                <iframe src="{{ $resolvedMapSrc }}" width="100%" height="100%" style="border:0; min-height: 520px; border-radius: 12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- form-map-area-end -->


    @include('frontend.includes.backImage')

@endsection
