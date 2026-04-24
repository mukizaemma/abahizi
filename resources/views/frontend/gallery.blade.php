@extends('layouts.frontbase')

@section('content')

 
    <!-- gallery-area-start -->
    <div class="tp-gallery-3__area pt-120 pb-120">
        <div class="container">
            <ul class="nav nav-tabs justify-content-center mb-4" id="galleryTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">All</a>
                </li>
                @foreach($programs as $program)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="program-{{ $program->id }}-tab" data-bs-toggle="tab" href="#program-{{ $program->id }}" role="tab">{{ $program->title }}</a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="galleryTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="row">
                        @foreach($gallery as $image)
                            <div class="col-xl-4 col-lg-4 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".3s">
                                <div class="tp-gallery-3__item p-relative">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="">
                                    <div class="tp-gallery-3__icon">
                                        <a class="popup-image" href="{{ asset('storage/' . $image->image) }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @foreach($programs as $program)
                    @php
                        $programImages = $program->images->take(9);
                    @endphp
                    <div class="tab-pane fade" id="program-{{ $program->id }}" role="tabpanel">
                        <div class="row">
                            @forelse($programImages as $image)
                                <div class="col-xl-4 col-lg-4 col-md-6 mb-30 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".3s">
                                    <div class="tp-gallery-3__item p-relative">
                                        <img src="{{ asset('storage/' . $image->image) }}" alt="">
                                        <div class="tp-gallery-3__icon">
                                            <a class="popup-image" href="{{ asset('storage/' . $image->image) }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <p>No images found for {{ $program->title }}.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- gallery-area-end -->



@endsection
