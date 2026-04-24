@extends('layouts.frontbase')

@section('title', 'Our Model')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Model',
    ])

    <section class="page-standalone grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-stretch">
                <div class="col-lg-6 order-2 order-lg-1">
                    <article class="page-standalone-card h-100 d-flex flex-column">
                        <header class="page-standalone-card__head">
                            <span class="page-standalone-card__icon" aria-hidden="true"><i class="flaticon-giving"></i></span>
                            <div>
                                <p class="page-standalone-card__eyebrow">How change happens</p>
                                <h2 class="page-standalone-card__title mb-0">Our model</h2>
                            </div>
                        </header>
                        <div class="page-standalone-card__body postbox__text flex-grow-1">
                            @if(!empty($about->model_content))
                                {!! $about->model_content !!}
                            @else
                                <p class="lead mb-0" style="font-size: 1.15rem; line-height: 1.75; color: #333;">
                                    Our model combines skills training, mentorship, and market linkage so participants build sustainable livelihoods.
                                </p>
                            @endif
                        </div>
                    </article>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <figure class="page-standalone-media mb-0 h-100">
                        @if(!empty($about->model_image))
                            <div class="page-standalone-media__frame">
                                <img src="{{ asset('storage/images/' . $about->model_image) }}" alt="Our model diagram" class="page-standalone-media__img">
                            </div>
                        @elseif(!empty($about->image))
                            <div class="page-standalone-media__frame">
                                <img src="{{ asset('storage/images/' . $about->image) }}" alt="Abahizi Rwanda" class="page-standalone-media__img page-standalone-media__img--cover">
                            </div>
                        @else
                            <div class="page-standalone-media__placeholder d-flex align-items-center justify-content-center text-center p-4">
                                <div>
                                    <i class="flaticon-giving d-block mb-3" style="font-size: 2.5rem; opacity: 0.35;"></i>
                                    <p class="small text-muted mb-0">A visual overview of our model will appear here when available.</p>
                                </div>
                            </div>
                        @endif
                    </figure>
                </div>
            </div>
        </div>
    </section>

@endsection
