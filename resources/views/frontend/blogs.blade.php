@extends('layouts.frontbase')

@section('title', __('site.nav.updates'))

@section('content')

    @include('frontend.includes.page-header', [
        'pageKey' => 'updates',
        'title' => __('site.updates.title'),
        'caption' => __('site.updates.lead'),
    ])

    <section class="updates-widget" aria-labelledby="updates-widget-title">
        <div class="container">
            <header class="updates-widget__intro">
                <h2 id="updates-widget-title" class="updates-widget__heading">{{ __('site.updates.heading') }}</h2>
            </header>

            @if(!$featured)
                <div class="updates-widget__empty">
                    <p class="mb-0">{{ __('site.updates.empty') }}</p>
                </div>
            @else
                <div class="updates-widget__stage{{ $rail->isEmpty() ? ' updates-widget__stage--solo' : '' }}">
                    <article class="updates-widget__featured">
                        <a href="{{ route('postSingle', $featured->slug) }}" class="updates-widget__featured-link">
                            @if($featured->coverUrl())
                                <img src="{{ $featured->coverUrl() }}" alt="{{ $featured->title }}" class="updates-widget__featured-img">
                            @else
                                <div class="updates-widget__featured-fallback" aria-hidden="true"></div>
                            @endif
                            <div class="updates-widget__featured-copy">
                                <span class="updates-widget__badge">{{ __('site.updates.latest_badge') }}</span>
                                <time datetime="{{ optional($featured->displayDate())->toDateString() }}">
                                    {{ optional($featured->displayDate())->format('d M Y') }}
                                </time>
                                <h3>{{ $featured->title }}</h3>
                                @if($featured->previewText(150))
                                    <p>{{ $featured->previewText(150) }}</p>
                                @endif
                                <span class="updates-widget__cta">{{ __('site.updates.read') }} <span aria-hidden="true">→</span></span>
                            </div>
                        </a>
                    </article>

                    @if($rail->isNotEmpty())
                    <div class="updates-widget__rail" aria-label="{{ __('site.updates.more_title') }}">
                        @foreach($rail as $update)
                            <article class="updates-widget__rail-card">
                                <a href="{{ route('postSingle', $update->slug) }}" class="updates-widget__rail-link">
                                    <div class="updates-widget__rail-media">
                                        @if($update->coverUrl())
                                            <img src="{{ $update->coverUrl() }}" alt="{{ $update->title }}" loading="lazy">
                                        @else
                                            <div class="updates-widget__rail-fallback" aria-hidden="true"></div>
                                        @endif
                                    </div>
                                    <div class="updates-widget__rail-body">
                                        <time datetime="{{ optional($update->displayDate())->toDateString() }}">
                                            {{ optional($update->displayDate())->format('d M Y') }}
                                        </time>
                                        <h3>{{ $update->title }}</h3>
                                        @if($update->previewText(70))
                                            <p class="updates-widget__rail-preview">{{ $update->previewText(70) }}</p>
                                        @endif
                                        <span>{{ __('site.updates.read') }} <span aria-hidden="true">→</span></span>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                    @endif
                </div>

                @if($moreNews->isNotEmpty())
                    <div class="updates-widget__more-bar">
                        <button
                            type="button"
                            class="updates-widget__more-btn"
                            data-updates-toggle="#updates-more-grid"
                            aria-expanded="false"
                            aria-controls="updates-more-grid"
                        >
                            <span data-updates-more-label>{{ __('site.updates.view_more') }}</span>
                            <span data-updates-less-label hidden>{{ __('site.updates.view_less') }}</span>
                        </button>
                    </div>

                    <div id="updates-more-grid" class="updates-widget__grid" hidden>
                        @foreach($moreNews as $update)
                            <article class="updates-widget__grid-card">
                                <a href="{{ route('postSingle', $update->slug) }}" class="updates-widget__grid-link">
                                    <div class="updates-widget__grid-media">
                                        @if($update->coverUrl())
                                            <img src="{{ $update->coverUrl() }}" alt="{{ $update->title }}" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="updates-widget__grid-body">
                                        <time datetime="{{ optional($update->displayDate())->toDateString() }}">
                                            {{ optional($update->displayDate())->format('d M Y') }}
                                        </time>
                                        <h3>{{ $update->title }}</h3>
                                        @if($update->previewText(110))
                                            <p>{{ $update->previewText(110) }}</p>
                                        @endif
                                        <span class="updates-widget__grid-read">{{ __('site.updates.read') }} <span aria-hidden="true">→</span></span>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>

    @include('frontend.includes.backImage')

@endsection
