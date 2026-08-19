@php
    $galleryItems = collect($factoryGallery ?? [])->map(function ($galleryImage) {
        $url = $galleryImage instanceof \App\Models\FactoryGalleryImage
            ? $galleryImage->url()
            : (str_contains((string) ($galleryImage->image ?? ''), '/')
                ? asset('storage/' . ltrim($galleryImage->image, '/'))
                : asset('storage/images/gallery/' . ($galleryImage->image ?? '')));

        return [
            'url' => $url,
            'caption' => trim((string) ($galleryImage->caption ?? '')),
            'alt' => trim((string) ($galleryImage->caption ?? '')) !== ''
                ? $galleryImage->caption
                : __('site.factory.gallery_alt'),
        ];
    })->filter(fn ($item) => $item['url'] !== '');
@endphp

@if($galleryItems->isNotEmpty())
    <section class="lux-section factory-gallery" aria-labelledby="factory-gallery-title">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
                <h2 id="factory-gallery-title" class="lux-section-head__title mb-3">{{ __('site.factory.gallery_title') }}</h2>
                <p class="lux-lead mb-0 mx-auto" style="max-width: 38rem;">{{ __('site.factory.gallery_lead') }}</p>
            </div>

            <div class="factory-gallery__mosaic" data-count="{{ $galleryItems->count() }}">
                @foreach($galleryItems as $item)
                    <a
                        href="{{ $item['url'] }}"
                        class="factory-gallery__item popup-image{{ $loop->first && $galleryItems->count() > 1 ? ' is-featured' : '' }}"
                    >
                        <img
                            src="{{ $item['url'] }}"
                            alt="{{ $item['alt'] }}"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            decoding="async"
                        >
                        @if($item['caption'] !== '')
                            <span class="factory-gallery__caption">{{ $item['caption'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
