<div class="home-mission-core-wrap">
    @php
        $parseStructuredBlock = function (?string $rawText, string $fallback): array {
            $source = trim((string) ($rawText ?? ''));
            if ($source === '') {
                $source = $fallback;
            }

            // Preserve block boundaries from rich text before stripping tags.
            $source = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $source);
            $source = preg_replace('/<\s*\/p\s*>/i', "\n", $source);
            $source = preg_replace('/<\s*\/li\s*>/i', "\n", $source);
            $source = preg_replace('/<\s*li[^>]*>/i', "- ", $source);
            $source = strip_tags(html_entity_decode($source));

            $lines = preg_split('/\r\n|\r|\n/', $source);
            $lines = array_values(array_filter(array_map(function ($line) {
                $line = trim($line);
                $line = preg_replace('/\s+/', ' ', $line);
                return trim((string) $line);
            }, $lines), fn ($line) => $line !== ''));

            $intro = '';
            $items = [];
            $outro = '';

            if ($lines === []) {
                return [$intro, $items, $outro];
            }

            if (count($lines) === 1 && str_contains($lines[0], ':')) {
                [$left, $right] = explode(':', $lines[0], 2);
                $intro = trim($left) . ':';
                $lines = array_values(array_filter(array_map('trim', preg_split('/\s*,\s*/', trim($right))), fn ($v) => $v !== ''));
            } elseif (str_ends_with($lines[0], ':')) {
                $intro = $lines[0];
                $lines = array_slice($lines, 1);
            } else {
                $intro = $lines[0];
                $lines = array_slice($lines, 1);
            }

            $expanded = [];
            foreach ($lines as $line) {
                $line = ltrim($line, "-*• \t");
                if (str_contains($line, ',')) {
                    $parts = array_values(array_filter(array_map('trim', explode(',', $line)), fn ($v) => $v !== ''));
                    foreach ($parts as $part) {
                        $expanded[] = $part;
                    }
                } else {
                    $expanded[] = $line;
                }
            }

            if (count($expanded) > 1) {
                $outro = array_pop($expanded);
            }
            $items = $expanded;

            return [$intro, $items, $outro];
        };
    @endphp

    {{-- Expertise + impact: managed from Admin > About > Problem, solution & manufacturing story --}}
    <section class="home-mv-strip grey-bg">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-12 text-center">
                    <h4 class="tp-section-title mb-0">Our Expertise &amp; Impact Through Manufacturing</h4>
                </div>
            </div>

            <div class="row g-4 about-home-pillars align-items-stretch">
                <div class="col-md-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".15s">
                    <article class="about-home-pillar h-100">
                        <div class="about-home-pillar__head">
                            <span class="about-home-pillar__icon" aria-hidden="true"><i class="flaticon-mission"></i></span>
                            <h4 class="about-home-pillar__title">Our Expertise</h4>
                        </div>
                        @php
                            [$expertiseIntro, $expertiseItems, $expertiseOutro] = $parseStructuredBlock(
                                $about->expertise_content ?? '',
                                "We combine mechanized production with handcrafted detailing:\nHand stitching and embellishments\nBeading and embroidery\nLeather craftsmanship\nCustom finishes and detailing\nWe operate under lean manufacturing principles, ensuring efficiency, consistency, and scalability."
                            );
                        @endphp
                        <p class="about-home-pillar__text mb-2">{{ $expertiseIntro }}</p>
                        @if(!empty($expertiseItems))
                            <ul class="about-home-pillar__list mb-2">
                                @foreach($expertiseItems as $item)
                                    <li>{{ ltrim($item, "-*• \t") }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if($expertiseOutro !== '')
                            <p class="about-home-pillar__text mb-0"><strong>{{ $expertiseOutro }}</strong></p>
                        @endif
                    </article>
                </div>
                <div class="col-md-6 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".25s">
                    <article class="about-home-pillar h-100">
                        <div class="about-home-pillar__head">
                            <span class="about-home-pillar__icon" aria-hidden="true"><i class="flaticon-vision"></i></span>
                            <h4 class="about-home-pillar__title">Our Impact Through Manufacturing</h4>
                        </div>
                        @php
                            [$impactIntro, $impactItems, $impactOutro] = $parseStructuredBlock(
                                $about->manufacturing_impact_content ?? '',
                                "Every product we create:\nProvides stable employment for women\nSupports families and households\nFunds community development programs\nBuilds Rwanda's reputation in ethical manufacturing\nManufacturing is not just our business-it is our impact engine."
                            );
                        @endphp
                        <p class="about-home-pillar__text mb-2">{{ $impactIntro }}</p>
                        @if(!empty($impactItems))
                            <ul class="about-home-pillar__list mb-2">
                                @foreach($impactItems as $item)
                                    <li>{{ ltrim($item, "-*• \t") }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if($impactOutro !== '')
                            <p class="about-home-pillar__text mb-0"><strong>{{ $impactOutro }}</strong></p>
                        @endif
                    </article>
                </div>
            </div>

        </div>
    </section>

    <div class="home-mission-core-accent" role="presentation" aria-hidden="true"></div>
</div>
<style>
    .about-home-pillar__list {
        margin: 0.5rem 0 0.85rem;
        padding-left: 1.15rem;
        list-style: none;
    }
    .about-home-pillar__list li {
        position: relative;
        margin-bottom: 0.45rem;
        line-height: 1.65;
        color: #2d2d2d;
        padding-left: 0.75rem;
    }
    .about-home-pillar__list li::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--brand-primary, #fad200);
        position: absolute;
        left: -0.2rem;
        top: 0.62rem;
    }
    @media (max-width: 991.98px) {
        
    }
</style>
