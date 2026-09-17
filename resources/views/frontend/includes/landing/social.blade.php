@php
    $socialItems = collect($homeGallery ?? [])
        ->filter(fn ($img) => ! empty($img->image))
        ->take(3)
        ->values()
        ->map(function ($img) {
            $src = method_exists($img, 'url') ? $img->url() : asset('storage/images/gallery/' . ltrim($img->image, '/'));

            return [
                'src' => $src,
                'title' => trim((string) ($img->caption ?? '')) ?: \App\Support\SiteCopy::get('social_title'),
            ];
        });

    $instagram = trim((string) ($setting->instagram ?? ''));
@endphp

@if($socialItems->isNotEmpty())
<section class="lh-social" aria-labelledby="lh-social-title">
    <div class="container">
        <div class="lh-social__head lh-reveal">
            <h2 id="lh-social-title" class="lh-social__title">{{ \App\Support\SiteCopy::get('social_title') }}</h2>
            @if($instagram !== '')
                <a href="{{ $instagram }}" class="lh-social__follow" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                    <span>{{ \App\Support\SiteCopy::get('social_cta') }}</span>
                </a>
            @endif
        </div>
        <div class="lh-social__grid">
            @foreach($socialItems as $index => $item)
                <button
                    type="button"
                    class="lh-social__item lh-reveal"
                    style="transition-delay: {{ $index * 0.06 }}s"
                    data-lh-gallery-open
                    data-lh-gallery-index="{{ $index }}"
                    aria-haspopup="dialog"
                    aria-controls="lh-social-lightbox"
                    aria-label="View {{ $item['title'] }}"
                >
                    <img src="{{ $item['src'] }}" alt="{{ $item['title'] }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" decoding="async">
                </button>
            @endforeach
        </div>
    </div>
</section>

<div
    class="lh-lightbox"
    id="lh-social-lightbox"
    hidden
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="lh-social-lightbox-title"
>
    <div class="lh-lightbox__backdrop" data-lh-gallery-close></div>
    <div class="lh-lightbox__dialog">
        <button type="button" class="lh-lightbox__close" data-lh-gallery-close aria-label="{{ __('site.landing.gallery_close') }}">
            <i class="fal fa-times" aria-hidden="true"></i>
        </button>
        <button type="button" class="lh-lightbox__nav lh-lightbox__nav--prev" data-lh-gallery-prev aria-label="{{ __('site.landing.gallery_prev') }}">
            <i class="fal fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button type="button" class="lh-lightbox__nav lh-lightbox__nav--next" data-lh-gallery-next aria-label="{{ __('site.landing.gallery_next') }}">
            <i class="fal fa-chevron-right" aria-hidden="true"></i>
        </button>
        <figure class="lh-lightbox__figure">
            <img src="" alt="" data-lh-gallery-image>
            <figcaption>
                <span id="lh-social-lightbox-title" data-lh-gallery-title></span>
                <span class="lh-lightbox__count" data-lh-gallery-count></span>
            </figcaption>
        </figure>
    </div>
</div>
<script type="application/json" id="lh-social-lightbox-data">@json($socialItems->values())</script>
@endif
