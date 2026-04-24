@extends('layouts.frontbase')

@section('title', 'Our Approach')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Approach',
    ])

    <section class="page-standalone grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10 col-xxl-9">
                    <article class="page-standalone-card">
                        <header class="page-standalone-card__head">
                            <span class="page-standalone-card__icon" aria-hidden="true"><i class="flaticon-mission"></i></span>
                            <div>
                                <p class="page-standalone-card__eyebrow">How we work</p>
                                <h2 class="page-standalone-card__title mb-0">Our approach</h2>
                            </div>
                        </header>
                        <div class="page-standalone-card__body postbox__text">
                            @if(!empty($about->approach_content))
                                {!! $about->approach_content !!}
                            @else
                                <p class="lead mb-0" style="font-size: 1.15rem; line-height: 1.75; color: #333;">
                                    We work alongside communities with practical training, mentoring, and holistic support—meeting people where they are and building lasting change together.
                                </p>
                            @endif
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

@endsection
