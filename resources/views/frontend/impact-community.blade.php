@extends('layouts.frontbase')

@section('title', __('site.nav.community'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'impact_community',
        'title' => __('site.nav.community'),
        'caption' => __('site.impact.community_lead'),
    ])

    <section class="lux-section impact-detail">
        <div class="container">
            @if(($initiatives ?? collect())->isEmpty())
                <div class="impact-hub__empty text-center">
                    <p class="mb-0">{{ __('site.impact.community_empty') }}</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($initiatives as $initiative)
                        <div class="col-md-6 col-lg-4">
                            <article class="impact-initiative-card h-100">
                                <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="impact-initiative-card__media d-block">
                                    @if(!empty($initiative->image))
                                        <img src="{{ asset('storage/' . ltrim($initiative->image, '/')) }}" alt="{{ $initiative->title }}" loading="lazy" decoding="async">
                                    @else
                                        <div class="impact-initiative-card__placeholder">{{ $initiative->title }}</div>
                                    @endif
                                </a>
                                <div class="impact-initiative-card__body">
                                    <h2 class="impact-initiative-card__title h3">
                                        <a href="{{ route('project', ['slug' => $initiative->slug]) }}">{{ $initiative->title }}</a>
                                    </h2>
                                    <p class="impact-initiative-card__excerpt mb-3">{{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($initiative->description ?? '')), 130, '…') }}</p>
                                    <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="impact-initiative-card__link">
                                        {{ __('site.home.updates_read') }} <span aria-hidden="true">→</span>
                                    </a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('frontend.includes.luxury.community-impact-split', ['mapOnly' => true])

    @include('frontend.includes.bottom')

@endsection
