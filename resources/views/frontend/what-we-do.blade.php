@extends('layouts.frontbase')

@section('title', 'What We Do')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'What We Do',
    ])

    <section class="page-standalone grey-bg pt-60 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10 col-xxl-9">
                    <article class="page-standalone-card">
                        <div class="page-standalone-card__body postbox__text">
                            @if(!empty($about->what_we_do))
                                {!! $about->what_we_do !!}
                            @else
                                <p class="lead mb-0" style="font-size: 1.15rem; line-height: 1.75; color: #333;">
                                    We combine ethical manufacturing with community programs that create lasting opportunity for women and families across Rwanda.
                                </p>
                            @endif
                        </div>
                    </article>

                    @if(!empty($about->how_it_works))
                        <article class="page-standalone-card mt-4">
                            <header class="page-standalone-card__head">
                                <span class="page-standalone-card__icon" aria-hidden="true"><i class="flaticon-mission"></i></span>
                                <div>
                                    <p class="page-standalone-card__eyebrow">Our approach</p>
                                    <h2 class="page-standalone-card__title mb-0">How it works</h2>
                                </div>
                            </header>
                            <div class="page-standalone-card__body postbox__text">
                                {!! $about->how_it_works !!}
                            </div>
                        </article>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
