@extends('layouts.frontbase')

@section('content')



        <!-- event-area-atart -->
        <div class="tp-event-details__area pt-120 pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-8">
                        <div class="tp-event-details__left-box">
                            <div class="tp-event-details__thumb p-relative pb-25">                   
                                <img src="{{asset('storage/images/events').$event->image}}" alt="">
                                {{-- <div class="tp-event-details__thumb-text d-none d-md-block">
                                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d M') }}</span>
                                </div> --}}
                            </div>
                            <h4 class="tp-event-details__title">{{ $event->title }}</h4>
                            <div class="tp-event-details__text pb-25">                   
                                <div class="postbox__text" style="font-size: 20px; font-weight: 700; text-align: left;">{!! $event->description !!}</div>
                            </div>

                            <a class="tp-btn" href="#">Register Now</a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="tp-event-details__right-box">                            
                            {{-- <div class="tp-event-details__author-info mb-30 grey-bg d-flex align-items-center">
                                <div class="tp-event-details__author-thumb">
                                    <img src="assets/img/event/author.png" alt="">
                                </div>
                                <div class="tp-event-details__author-text">
                                    <a href="#"><h5>Kaira Beasley</h5></a>
                                    <span>Host & Speaker</span>
                                </div>
                            </div> --}}
                            <div class="tp-event-details__contact-box mb-30">
                                <ul>
                                    <li>
                                        <div class="tp-event-details__contact-item d-flex align-items-start">
                                            <div class="tp-event-details__contact-icon">
                                                <span><i class="flaticon-time"></i></span>
                                            </div>
                                            <div class="tp-event-details__contact-text">
                                                <span>Event Time:</span>
                                                <b>{{$event->timeStart}} - {{$event->timeEnd}}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tp-event-details__contact-item d-flex align-items-start">
                                            <div class="tp-event-details__contact-icon">
                                                <span><i class="flaticon-calendar"></i></span>
                                            </div>
                                            <div class="tp-event-details__contact-text">
                                                <span>Date:</span>
                                                <b>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tp-event-details__contact-item d-flex align-items-start">
                                            <div class="tp-event-details__contact-icon">
                                                <span><i class="flaticon-location"></i></span>
                                            </div>
                                            <div class="tp-event-details__contact-text">
                                                <span>Location:</span>
                                                <a href="#"><b>{{ $event->location }}</b></a>                                                
                                            </div>
                                        </div></li>

                                    <li>
                                        <div class="tp-event-details__contact-item d-flex align-items-start">
                                            <div class="tp-event-details__contact-icon">
                                                <span><i class="flaticon-phone"></i></span>
                                            </div>
                                            <div class="tp-event-details__contact-text">
                                                <span>More Details, Call:</span>
                                                <a href="tel:{{ $event->registerContact }}"><b>{{ $event->registerContact }}</b></a>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- event-area-end -->



@endsection
