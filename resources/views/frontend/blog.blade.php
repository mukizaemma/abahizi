@extends('layouts.frontbase')

@section('content')



<!-- postbox area start -->
<section class="postbox__area pt-120 pb-80">
<div class="container">
    <div class="row">
        <div class="col-xxl-8 col-xl-8 col-lg-8">
            <div class="postbox__wrapper">
            <article class="postbox__item format-image mb-50 transition-3">
                <div class="postbox__thumb p-relative m-img">
                    <img src="{{ asset('storage/images/news/' . $blog->image) }}" alt="">
                </div>
                <div class="postbox__content">
                    <h3 class="postbox__title">{{ $blog->title }}</h3>
                    <div class="postbox__text" style="font-size: 20px; font-weight: 700; text-align: left;">
                        {!! $blog->body !!}
                    </div>
                </div>
            </article>

        <div class="tp-gallery-3__area pt-120 pb-120">
            <div class="container">
                <div class="row">
                    @if($images->count() == 0)
                            <p class="text-muted">No images yet.</p>
                        @else
                        <h3 class="postbox__title">Blog Gallery</h3>
                        @foreach ($images as $image)
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s"

                        data-wow-delay=".3s">
                            <div class="tp-gallery-3__item p-relative">
                                <img src="{{ asset('storage/' . $image->gallery) }}" alt="">
                                <div class="tp-gallery-3__icon">
                                    <a class="popup-image" href="{{ asset('storage/' . $image->gallery) }}">
                                        <svg id="body" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="49" height="51" viewBox="0 0 49 51">
                                            <g id="gallery">
                                            </g>
                                        </svg>
                                    </a>                        
                                </div>
                            </div>
                        </div>
                        @endforeach

                    @endif
                </div>
            </div>
        </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-lg-4">
            <div class="sidebar__wrapper">

            <div class="sidebar__widget mb-30">
                <h3 class="sidebar__widget-title">Related Projects</h3>
                <div class="sidebar__widget-content">
                    <div class="sidebar__post">

                        @foreach ($relatedBlogs as $rs)
                        <div class="rc__post mb-10 d-flex align-items-center">
                        <div class="rc__post-thumb mr-20">
                            <a href="{{route('postSingle',$rs->slug)}}"><img src="{{ asset('storage/images/news/' . $rs->image) }}" alt="" width="90px"></a>
                        </div>
                        <div class="rc__post-content">
                            {{-- <div class="rc__meta">
                                <span><i class="flaticon-comment"></i>
                                02 Comments</span>
                            </div> --}}
                            <h3 class="rc__post-title">
                                <a href="{{route('postSingle',$rs->slug)}}">{{ $rs->title }}</a>
                            </h3>
                        </div>
                        </div>
                        @endforeach


                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- postbox area end -->



@endsection
