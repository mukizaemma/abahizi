@php
    $ctaAbout = $about ?? \App\Models\Background::firstOrEmpty();
    $ctaFile = $ctaAbout->image2 ?? $ctaAbout->image ?? $ctaAbout->image1 ?? '';
    $ctaBgUrl = $ctaFile !== ''
        ? (str_starts_with((string) $ctaFile, 'http')
            ? $ctaFile
            : asset('storage/images/' . ltrim($ctaFile, '/')))
        : asset('assets/img/cta/cta-bg-3.jpg');

    $impactQuote = trim(strip_tags(html_entity_decode($ctaAbout->manufacturing_impact_content ?? '')));
    if ($impactQuote === '') {
        $impactQuote = 'We manufacture high-quality handbags and accessories for global brands—turning ethical production into meaningful impact for women, families, and communities across Rwanda.';
    } else {
        $impactQuote = \Illuminate\Support\Str::limit($impactQuote, 300, '…');
    }
@endphp

<section class="site-impact-quote" aria-labelledby="site-impact-quote-heading">
    <div
        class="site-impact-quote__bg wow tpfadeUp"
        data-wow-duration=".9s"
        data-wow-delay=".2s"
        data-background="{{ $ctaBgUrl }}"
    >
        <div class="container">
            <div class="site-impact-quote__inner text-center">
                <p class="site-impact-quote__eyebrow">Manufacturing with purpose</p>
                <blockquote class="site-impact-quote__quote" id="site-impact-quote-heading">
                    <span class="site-impact-quote__mark" aria-hidden="true">“</span>
                    {{ $impactQuote }}
                    <span class="site-impact-quote__mark site-impact-quote__mark--end" aria-hidden="true">”</span>
                </blockquote>
                @if(!empty($navProgramOurImpact))
                    <div class="site-impact-quote__actions">
                        <a
                            class="tp-btn site-impact-quote__btn"
                            href="{{ route('programShow', ['slug' => $navProgramOurImpact->slug]) }}"
                        >
                            Our Impacts <span aria-hidden="true">→</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
