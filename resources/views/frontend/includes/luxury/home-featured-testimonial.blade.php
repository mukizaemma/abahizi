@php
    $featured = ($testimonials ?? collect())->first();
@endphp

@if($featured)
    @php
        $raw = html_entity_decode($featured->testimony ?? '');
        $raw = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $raw);
        $raw = preg_replace('/<\s*\/p\s*>/i', "\n", $raw);
        $testPlain = trim(preg_replace('/\s+/', ' ', strip_tags($raw)));
        $len = strlen($testPlain);
        if ($len > 40) {
            $half = trim(substr($testPlain, 0, (int) ($len / 2)));
            $second = trim(substr($testPlain, (int) ($len / 2)));
            if ($half !== '' && $half === $second) {
                $testPlain = $half;
            }
        }
        $quote = \Illuminate\Support\Str::limit($testPlain, 320, '…');
        $imageUrl = !empty($featured->image) ? asset('storage/' . $featured->image) : null;
    @endphp

    <section class="home-featured-testimonial pt-90 pb-90 grey-bg" aria-labelledby="home-featured-testimonial-title">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-center">
                @if($imageUrl)
                    <div class="col-lg-5 wow tpfadeUp" data-wow-duration=".9s">
                        <div class="home-featured-testimonial__media">
                            <img src="{{ $imageUrl }}" alt="{{ $featured->names ?? 'Community member' }}" loading="lazy">
                        </div>
                    </div>
                @endif
                <div class="{{ $imageUrl ? 'col-lg-7' : 'col-12' }} lux-section-head lux-section-head--solo wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".15s">
                    <h2 id="home-featured-testimonial-title" class="lux-section-head__title mb-3">{{ __('site.home.testimonial_eyebrow') }}</h2>
                    <blockquote class="home-featured-testimonial__quote">
                        <span class="home-featured-testimonial__mark" aria-hidden="true">"</span>
                        {{ $quote }}
                    </blockquote>
                    @if(!empty($featured->names))
                        <p class="home-featured-testimonial__author mb-4">{{ $featured->names }}</p>
                    @endif
                    <a href="{{ route('testimonials') }}" class="tp-btn">{{ __('site.home.testimonial_cta') }} <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>
    </section>
@endif
