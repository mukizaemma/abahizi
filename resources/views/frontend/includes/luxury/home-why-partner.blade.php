@php
    $plainText = static function (?string $html): string {
        $text = html_entity_decode((string) ($html ?? ''));
        $text = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $text);
        $text = preg_replace('/<\s*\/p\s*>/i', "\n", $text);
        $text = preg_replace('/<\s*\/li\s*>/i', "\n", $text);
        $text = strip_tags($text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    };

    $intro = $plainText($about->solution_statement ?? '');
    if ($intro === '') {
        $intro = 'Abahizi Rwanda is a full-service bag manufacturing partner in Masoro—combining export-ready production with a workforce invested in quality, consistency, and long-term collaboration.';
    } else {
        $intro = \Illuminate\Support\Str::limit($intro, 320, '…');
    }

    $reasons = [
        ['icon' => 'fa-ruler-combined', 'title' => 'Custom bag development', 'desc' => 'Totes, crossbody bags, pouches, and embellished pieces built to your materials, hardware, and brand specs.'],
        ['icon' => 'fa-industry', 'title' => 'Scalable CMT production', 'desc' => 'Lean manufacturing from sampling through bulk orders—with multi-point quality checks before export.'],
        ['icon' => 'fa-gem', 'title' => 'Artisan craftsmanship', 'desc' => 'Hand stitching, leather work, beading, and finishing by a skilled, full-time production team.'],
        ['icon' => 'fa-handshake', 'title' => 'Reliable partnership', 'desc' => 'Clear timelines, responsive communication, and a factory team focused on meeting partner expectations.'],
        ['icon' => 'fa-people-group', 'title' => 'Empowered workforce', 'desc' => 'Stable employment, skills training, and community support that strengthen retention and workmanship.'],
        ['icon' => 'fa-earth-africa', 'title' => 'Made in Rwanda', 'desc' => 'Ethical production rooted in Masoro—delivering quality bags for regional and global markets.'],
    ];
@endphp

<section class="home-why-partner lux-section" aria-labelledby="home-why-partner-title">
    <div class="container">
        <div class="home-why-partner__head text-center wow tpfadeUp" data-wow-duration=".9s">
            <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.why_partner_eyebrow') }}</p>
            <h2 id="home-why-partner-title" class="home-why-partner__title">{{ __('site.home.why_partner_title') }}</h2>
            <p class="home-why-partner__lead mx-auto">{{ $intro }}</p>
        </div>

        <div class="home-why-partner__grid">
            @foreach($reasons as $i => $reason)
                <article class="home-why-partner__item wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format($i * 0.06, 2) }}s">
                    <span class="home-why-partner__icon" aria-hidden="true"><i class="fas {{ $reason['icon'] }}"></i></span>
                    <div class="home-why-partner__item-body">
                        <h3 class="home-why-partner__item-title">{{ $reason['title'] }}</h3>
                        <p class="home-why-partner__item-desc mb-0">{{ $reason['desc'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
