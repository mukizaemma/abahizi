@php
    $highlightItems = collect();

    foreach (($images ?? collect()) as $image) {
        $url = trim((string) ($image->image ?? ''));
        if ($url === '') {
            continue;
        }
        $full = str_starts_with($url, 'http') ? $url : asset('storage/' . ltrim($url, '/'));
        $highlightItems->push([
            'url' => $full,
            'caption' => trim((string) ($image->caption ?? '')),
            'alt' => trim((string) ($image->caption ?? '')) !== '' ? $image->caption : ($activity->title . ' highlight'),
        ]);
    }

    foreach (($programGallery ?? collect()) as $image) {
        $url = trim((string) ($image->image ?? ''));
        if ($url === '') {
            continue;
        }
        $full = str_starts_with($url, 'http') ? $url : asset('storage/' . ltrim($url, '/'));
        if ($highlightItems->contains(fn ($item) => $item['url'] === $full)) {
            continue;
        }
        $highlightItems->push([
            'url' => $full,
            'caption' => trim((string) ($image->caption ?? '')),
            'alt' => trim((string) ($image->caption ?? '')) !== '' ? $image->caption : ($activity->title . ' highlight'),
        ]);
    }

    $highlightItems = $highlightItems->take(12);
@endphp

@if($highlightItems->isNotEmpty())
    <section class="lux-section initiative-highlights" aria-labelledby="initiative-highlights-title">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5 lux-section-head lux-section-head--solo">
                <h2 id="initiative-highlights-title" class="lux-section-head__title mb-3">{{ __('site.initiative.highlights_title') }}</h2>
                <p class="lux-lead mb-0 mx-auto" style="max-width: 38rem;">{{ __('site.initiative.highlights_lead') }}</p>
            </div>

            <div class="factory-gallery__mosaic" data-count="{{ $highlightItems->count() }}">
                @foreach($highlightItems as $item)
                    <a
                        href="{{ $item['url'] }}"
                        class="factory-gallery__item popup-image{{ $loop->first && $highlightItems->count() > 1 ? ' is-featured' : '' }}"
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
