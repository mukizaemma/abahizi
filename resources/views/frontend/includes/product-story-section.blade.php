@php
    $pts = $productStoryPoints ?? collect();
    $heading = trim((string) ($productStoryHeading ?? ''));
    $show = $pts->isNotEmpty() || $heading !== '';

    $storyBgFile = $about->core_values_background ?? $about->image2 ?? $about->image1 ?? $about->image ?? '';
    $storyBgUrl = $storyBgFile !== ''
        ? (str_starts_with((string) $storyBgFile, 'http')
            ? $storyBgFile
            : asset('storage/images/' . ltrim($storyBgFile, '/')))
        : '';
@endphp
@if($show)
<section class="product-story-section {{ $storyBgUrl ? 'product-story-section--parallax' : 'product-story-section--plain' }}"
    @if($storyBgUrl)
        style="--product-story-bg: url('{{ $storyBgUrl }}')"
    @endif
    aria-labelledby="product-story-heading">
    <div class="product-story-section__overlay">
    @if($heading !== '')
        <div class="product-story-section__banner">
            <div class="container">
                <h2 id="product-story-heading" class="product-story-section__title">{{ $heading }}</h2>
            </div>
        </div>
    @endif
    @if($pts->isNotEmpty())
        <div class="product-story-section__body">
            <div class="container py-4 py-lg-5">
                <ul class="product-story-section__list list-unstyled mb-0">
                    @foreach($pts as $idx => $point)
                        <li class="product-story-section__item">
                            <span class="product-story-section__check" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <div class="product-story-section__text-wrap">
                                <span class="product-story-section__number">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="product-story-section__text">{!! nl2br(e($point->content)) !!}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    </div>
</section>
@endif
