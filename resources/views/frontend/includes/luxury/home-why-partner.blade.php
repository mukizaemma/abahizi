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
        $intro = \Illuminate\Support\Str::limit($intro, 280, '…');
    }

    $splitImage = null;
    if (!empty($about->factory_services_image)) {
        $splitImage = asset('storage/images/' . $about->factory_services_image);
    } elseif (isset($homeGallery) && $homeGallery->isNotEmpty() && !empty($homeGallery->first()->image)) {
        $splitImage = asset('storage/images/gallery/' . $homeGallery->first()->image);
    } else {
        $splitImage = asset('assets/img/breadcrumb/breadcrumb-bg-1.jpg');
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

<section class="home-why-partner" aria-labelledby="home-why-partner-title">
    <div class="container-fluid px-0">
        <div class="row g-0 align-items-stretch home-why-partner__row">
            <div class="col-lg-6 col-xl-7 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                <div class="home-why-partner__content h-100">
                    <p class="lux-section-head__eyebrow mb-2">{{ __('site.home.why_partner_eyebrow') }}</p>
                    <h2 id="home-why-partner-title" class="home-why-partner__title">{{ __('site.home.why_partner_title') }}</h2>
                    <p class="home-why-partner__lead">{{ $intro }}</p>

                    <div class="home-why-partner__grid">
                        @foreach($reasons as $reason)
                            <article class="home-why-partner__item">
                                <span class="home-why-partner__icon" aria-hidden="true"><i class="fas {{ $reason['icon'] }}"></i></span>
                                <div>
                                    <h3 class="home-why-partner__item-title">{{ $reason['title'] }}</h3>
                                    <p class="home-why-partner__item-desc mb-0">{{ $reason['desc'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="col-lg-6 col-xl-5 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                <div class="home-why-partner__visual h-100" style="background-image: url('{{ $splitImage }}');">
                    <div class="home-why-partner__visual-overlay">
                        <div class="home-why-partner__visual-card">
                            <p class="home-why-partner__visual-label">{{ __('site.home.why_partner_visual_label') }}</p>
                            <p class="home-why-partner__visual-stat">{{ $about->handbags_exported ?? '310,000+' }}</p>
                            <p class="home-why-partner__visual-caption">{{ __('site.stats.handbags') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
