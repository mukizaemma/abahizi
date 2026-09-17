@php
    $socialImages = collect();
    if (isset($homeGallery)) {
        foreach ($homeGallery as $img) {
            if (! empty($img->image)) {
                $socialImages->push(asset('storage/images/gallery/' . ltrim($img->image, '/')));
            }
        }
    }
    if (isset($homeProducts)) {
        foreach ($homeProducts as $product) {
            if (! empty($product->image)) {
                $socialImages->push(asset('storage/' . ltrim($product->image, '/')));
            }
        }
    }
    foreach (['image1', 'image2', 'image3', 'image'] as $field) {
        if (! empty($about->{$field} ?? null)) {
            $socialImages->push(asset('storage/images/' . ltrim($about->{$field}, '/')));
        }
    }
    $socialImages = $socialImages->filter()->unique()->values();
    while ($socialImages->count() > 0 && $socialImages->count() < 6) {
        $socialImages->push($socialImages[$socialImages->count() % max(1, $socialImages->count())]);
    }
    $socialImages = $socialImages->take(6);
    $instagram = trim((string) ($setting->instagram ?? ''));
@endphp

@if($socialImages->isNotEmpty())
<section class="lh-social" aria-labelledby="lh-social-title">
    <div class="container">
        <div class="lh-social__head lh-reveal">
            <h2 id="lh-social-title" class="lh-social__title">{{ \App\Support\SiteCopy::get('social_title') }}</h2>
            @if($instagram !== '')
                <a href="{{ $instagram }}" class="lh-social__follow" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                    <span>{{ \App\Support\SiteCopy::get('social_cta') }}</span>
                </a>
            @else
                <span class="lh-social__follow" aria-hidden="true">
                    <i class="fab fa-instagram"></i>
                </span>
            @endif
        </div>
        <div class="lh-social__grid">
            @foreach($socialImages as $index => $src)
                @php
                    $itemTag = $instagram !== '' ? 'a' : 'div';
                    $itemHref = $instagram !== '' ? ' href="'.$instagram.'" target="_blank" rel="noopener noreferrer"' : '';
                @endphp
                <{{ $itemTag }} class="lh-social__item lh-reveal"{!! $itemHref !!} style="transition-delay: {{ $index * 0.06 }}s">
                    <img src="{{ $src }}" alt="" loading="lazy" decoding="async">
                </{{ $itemTag }}>
            @endforeach
        </div>
    </div>
</section>
@endif
