{{--
  Featured hero image + thumbnail strip (professional gallery).
  Expects: $galleryItems (collection with ->image), $heading (string), $galleryKey (unique string)
--}}
@php
    $galleryKey = $galleryKey ?? 'gallery';
@endphp
@if($galleryItems->count() > 0)
    @php
        $first = $galleryItems->first();
        $firstUrl = asset('storage/' . $first->image);
    @endphp
    @if(!empty(trim($heading ?? '')))
        <h3 class="h5 mb-3 activity-gallery-heading">{{ $heading }}</h3>
    @endif
    <div class="gallery-featured mb-4 mb-lg-5" id="gallery-{{ $galleryKey }}" data-gallery-featured>
        <a href="{{ $firstUrl }}" class="gallery-featured__main-link popup-image d-block">
            <img src="{{ $firstUrl }}" alt="" class="gallery-featured__main-img w-100" data-gallery-main width="800" height="450">
        </a>
        @if($galleryItems->count() > 1)
            <div class="gallery-featured__thumbs-wrap mt-3">
                <div class="gallery-featured__thumbs d-flex gap-2 gap-md-3">
                    @foreach($galleryItems as $item)
                        @php $imgUrl = asset('storage/' . $item->image); @endphp
                        <button type="button"
                            class="gallery-featured__thumb-btn {{ $loop->first ? 'is-active' : '' }}"
                            data-full-url="{{ $imgUrl }}"
                            aria-label="Gallery image {{ $loop->iteration }}"
                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                            <img src="{{ $imgUrl }}" alt="" width="88" height="88" loading="lazy" class="gallery-featured__thumb-img">
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif
