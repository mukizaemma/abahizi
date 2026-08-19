@extends('layouts.frontbase')

@section('title', $blog->title)

@section('content')
@php
    $galleryItems = ($images ?? collect())->filter(fn ($image) => filled($image->imageUrl()));
    $shareUrl = url()->current();
    $shareText = $blog->title;
@endphp

<article class="update-article">
    @if($blog->coverUrl())
        <header class="update-hero">
            <div class="update-hero__media" aria-hidden="true">
                <img src="{{ $blog->coverUrl() }}" alt="">
            </div>
            <div class="update-hero__overlay">
                <div class="container">
                    <a class="update-hero__back" href="{{ route('posts') }}">&larr; {{ __('site.updates.back') }}</a>
                    <time class="update-hero__date" datetime="{{ optional($blog->displayDate())->toDateString() }}">
                        {{ optional($blog->displayDate())->format('d M Y') }}
                    </time>
                    <h1 class="update-hero__title">{{ $blog->title }}</h1>
                    @if(!empty($blog->author))
                        <p class="update-hero__by">{{ __('site.updates.by') }} {{ $blog->author }}</p>
                    @endif
                </div>
            </div>
        </header>
    @else
        <header class="update-hero update-hero--plain">
            <div class="container">
                <a class="update-hero__back update-hero__back--dark" href="{{ route('posts') }}">&larr; {{ __('site.updates.back') }}</a>
                <time class="update-hero__date" datetime="{{ optional($blog->displayDate())->toDateString() }}">
                    {{ optional($blog->displayDate())->format('d M Y') }}
                </time>
                <h1 class="update-hero__title update-hero__title--dark">{{ $blog->title }}</h1>
                @if(!empty($blog->author))
                    <p class="update-hero__by update-hero__by--dark">{{ __('site.updates.by') }} {{ $blog->author }}</p>
                @endif
            </div>
        </header>
    @endif

    <div class="container">
        <div class="update-article__layout">
            <aside class="update-article__share" aria-label="{{ __('site.updates.share') }}">
                <span>{{ __('site.updates.share') }}</span>
                <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                </a>
                <button type="button" class="update-article__copy" data-copy-link="{{ $shareUrl }}" aria-label="{{ __('site.updates.copy_link') }}">
                    <i class="far fa-link" aria-hidden="true"></i>
                </button>
            </aside>

            <div class="update-article__main">
                @if(!empty($blog->body))
                    <div class="update-article__body postbox__text">
                        {!! $blog->body !!}
                    </div>
                @endif

                @if($galleryItems->isNotEmpty())
                    <section class="update-article__gallery" aria-labelledby="update-gallery-title">
                        <div class="update-article__gallery-head">
                            <h2 id="update-gallery-title">{{ __('site.updates.gallery') }}</h2>
                            <p>{{ $galleryItems->count() }} {{ __('site.updates.photos') }}</p>
                        </div>
                        <div class="update-article__gallery-grid update-article__gallery-grid--{{ min(3, $galleryItems->count()) }}">
                            @foreach($galleryItems as $image)
                                <a class="update-article__gallery-item popup-image" href="{{ $image->imageUrl() }}">
                                    <img src="{{ $image->imageUrl() }}" alt="{{ $image->caption ?: $blog->title }}" loading="lazy">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</article>

@if($relatedBlogs->isNotEmpty())
    <section class="update-related" aria-labelledby="update-related-title">
        <div class="container">
            <div class="update-related__head">
                <h2 id="update-related-title">{{ __('site.updates.more_title') }}</h2>
                <a href="{{ route('posts') }}" class="update-related__all">{{ __('site.home.updates_cta') }} <span aria-hidden="true">→</span></a>
            </div>
            <div class="update-related__grid">
                @foreach($relatedBlogs as $rs)
                    <article class="update-related__card">
                        <a href="{{ route('postSingle', $rs->slug) }}" class="update-related__link">
                            <div class="update-related__media">
                                @if($rs->coverUrl())
                                    <img src="{{ $rs->coverUrl() }}" alt="{{ $rs->title }}" loading="lazy">
                                @else
                                    <div class="update-related__fallback">{{ __('site.nav.updates') }}</div>
                                @endif
                            </div>
                            <div class="update-related__body">
                                <time datetime="{{ optional($rs->displayDate())->toDateString() }}">
                                    {{ optional($rs->displayDate())->format('d M Y') }}
                                </time>
                                <h3>{{ $rs->title }}</h3>
                                @if($rs->previewText(110))
                                    <p>{{ $rs->previewText(110) }}</p>
                                @endif
                                <span>{{ __('site.updates.read') }} <span aria-hidden="true">→</span></span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
