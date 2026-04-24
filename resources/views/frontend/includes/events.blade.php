        <div class="tp-event__area pt-115 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="tp-event__section-title text-center">
                            <h4 class="tp-section-title">Upcoming Events </h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($events as $event)
                    <div class="col-xl-6 col-lg-6 col-sm-12 mb-30 wow tpfadeUp" data-wow-duration=".9s"
                    data-wow-delay=".3s">
                        <div class="tp-event__wrapper">
                            <div class="tp-event__item">
                                <div class="tp-event__thumb p-relative">
                                    <img src="{{asset('storage/images/events/').$event->image}}" alt="">
                                    {{-- <div class="tp-event__thumb-text">
                                        <h4 class="tp-event__thumb-date">
                                            {{ \Carbon\Carbon::parse($event->date)->format('d') }} <br>
                                            <span>{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                        </h4>

                                    </div> --}}
                                </div>
                                <div class="tp-event__content">
                                    <div class="tp-event__meta">
                                        <span><i class="far fa-clock"></i>{{$event->timeStart}} - {{$event->timeEnd}}</span>
                                        <a target="_blank" href=""><span><i class="fal fa-map-marker-alt"></i>
                                            {{$event->location}}</span></a>
                                    </div>
                                    <a href="{{ route('event', ['slug' => $event->slug]) }}">
                                        <h4 class="tp-event__title">{{$event->title}}</h4>
                                    </a>
                                    <div class="tp-event__link">

                                        @if(isset($event->slug))
                                        <a href="{{ route('event', ['slug' => $event->slug]) }}">Read More<i class="fal fa-long-arrow-right"></i></a>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
        