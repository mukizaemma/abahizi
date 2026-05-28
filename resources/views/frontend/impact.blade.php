@extends('layouts.frontbase')

@section('title', 'Our Impact')

@section('content')

    @php
        $activeTab = in_array(request('tab'), ['empower', 'improve'], true) ? request('tab') : 'improve';
        $youtubeEmbed = static function (?string $url): ?string {
            $raw = trim((string) ($url ?? ''));
            if ($raw === '') {
                return null;
            }
            if (preg_match('~(?:youtube\.com/watch\?v=|youtube\.com/embed/|youtu\.be/)([A-Za-z0-9_-]{11})~', $raw, $m)) {
                return 'https://www.youtube.com/embed/' . $m[1];
            }
            return null;
        };
    @endphp

    @php
        $coreImpactStats = collect([
            ['label' => 'Families positively impacted', 'value' => $about->families_impacted ?? null],
            ['label' => 'Full-time artisans (90% women)', 'value' => $about->artisans_count ?? ($about->jobs_created ?? null)],
            ['label' => 'Hours of vocational & life-skills training', 'value' => $about->training_hours ?? null],
        ])->filter(fn ($item) => trim((string) ($item['value'] ?? '')) !== '');

        $impactHeaderStatsHtml = '';
        if ($coreImpactStats->isNotEmpty()) {
            $impactHeaderStatsHtml .= '<div class="page-header-stats" data-impact-stats-counter-section><div class="page-header-stats__grid">';
            foreach ($coreImpactStats as $stat) {
                $rawValue = trim((string) ($stat['value'] ?? ''));
                $digits = preg_replace('/[^\d]/', '', $rawValue);
                $counterTarget = $digits !== '' ? (int) $digits : 0;
                $impactHeaderStatsHtml .= '<article class="page-header-stats__stat">';
                $impactHeaderStatsHtml .= '<p class="page-header-stats__value" data-impact-counter-target="'.e((string) $counterTarget).'" data-impact-counter-final="'.e($rawValue).'">'.($counterTarget > 0 ? '0' : e($rawValue)).'</p>';
                $impactHeaderStatsHtml .= '<p class="page-header-stats__label">'.e((string) $stat['label']).'</p>';
                $impactHeaderStatsHtml .= '</article>';
            }
            $impactHeaderStatsHtml .= '</div></div>';
        }
    @endphp

    @include('frontend.includes.page-header', [
        'title' => 'Our Impact',
        'extraHtml' => $impactHeaderStatsHtml,
    ])

    <div class="lux-impact-shell pt-40 pb-90" x-data="{ tab: '{{ $activeTab }}' }">
        <div class="container">
            <div class="lux-impact-hub__tabs" role="tablist">
                <button type="button"
                        class="lux-impact-hub__tab"
                        :class="{ 'is-active': tab === 'empower' }"
                        @click="tab = 'empower'"
                        role="tab"
                        :aria-selected="tab === 'empower'">
                    Empower Workers
                </button>
                <button type="button"
                        class="lux-impact-hub__tab"
                        :class="{ 'is-active': tab === 'improve' }"
                        @click="tab = 'improve'"
                        role="tab"
                        :aria-selected="tab === 'improve'">
                    Improve Community
                </button>
            </div>

            <div class="lux-impact-panel" x-show="tab === 'empower'" x-transition.opacity.duration.250ms role="tabpanel">
                <div class="lux-impact-panel__head">
                    <h2 class="lux-impact-panel__title">Empower Workers</h2>
                    <p class="lux-impact-panel__subtitle mb-0">Artisan livelihoods, training, benefits, and leadership pathways.</p>
                </div>
                @include('frontend.includes.luxury.artisan-journey')

                @if(($impacts ?? collect())->isNotEmpty())
                    <div class="row g-4 mb-5 mt-2">
                        @foreach ($impacts as $item)
                            <div class="col-md-6 col-lg-4">
                                <article class="card border-0 shadow-sm h-100 impact-number-card impact-number-card--item">
                                    <div class="card-body p-4">
                                        @if(!empty($item->value))
                                            <p class="impact-number-card__value mb-1">{{ $item->value }}</p>
                                        @endif
                                        <h4 class="h5 mb-2">{{ $item->title }}</h4>
                                        @if(!empty($item->description))
                                            <p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 120, '…') }}</p>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="lux-impact-panel" x-show="tab === 'improve'" x-transition.opacity.duration.250ms role="tabpanel">
                <div class="lux-impact-panel__head">
                    <h2 class="lux-impact-panel__title">Improve Community</h2>
                    <p class="lux-impact-panel__subtitle mb-0">Community initiatives and shared infrastructure that strengthens Masoro and the wider district.</p>
                </div>
                @if(($initiatives ?? collect())->isEmpty())
                    <div class="row mb-5">
                        <div class="col-12 text-center">
                            <p class="text-muted mb-0" style="font-size: 18px;">Initiatives will appear here when they are published.</p>
                        </div>
                    </div>
                @else
                    <div class="row g-4 mb-5">
                        @foreach ($initiatives as $initiative)
                            <div class="col-md-6 col-lg-4 wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                                <article class="program-list-card h-100 d-flex flex-column bg-white rounded-3 overflow-hidden border shadow-sm">
                                    <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="program-list-card__thumb d-block position-relative">
                                        @if(!empty($initiative->image))
                                            <img src="{{ asset('storage/' . ltrim($initiative->image, '/')) }}" alt="{{ $initiative->title }}" class="w-100 program-list-card__img" loading="lazy" decoding="async">
                                        @else
                                            <div class="w-100 d-flex align-items-center justify-content-center program-list-card__img text-muted" style="background:#efefef;">No image</div>
                                        @endif
                                    </a>
                                    <div class="p-4 d-flex flex-column flex-grow-1">
                                        <h4 class="h5 mb-2">
                                            <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="text-dark text-decoration-none">{{ $initiative->title }}</a>
                                        </h4>
                                        <p class="program-list-card__excerpt mb-4">{{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($initiative->description ?? '')), 150, '…') }}</p>
                                        <a href="{{ route('project', ['slug' => $initiative->slug]) }}" class="tp-btn align-self-start mt-auto">Read more</a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif

                @include('frontend.includes.luxury.community-impact-split')
            </div>
        </div>
        <div class="container mt-5">
            @include('frontend.includes.luxury.impact-download-center')
        </div>
    </div>

    <style>
        .impact-number-card { border-radius: 14px; overflow: hidden; }
        .impact-number-card__value {
            font-size: clamp(1.4rem, 3.8vw, 2rem);
            line-height: 1.15;
            font-weight: 800;
            color: var(--brand-secondary, #2c2c2c);
        }
        .impact-number-card--item .impact-number-card__value {
            color: var(--brand-primary, #c9a962);
        }
        .impact-testimonial-card { border-radius: 14px; overflow: hidden; }
        .impact-testimonial-card__thumb img {
            height: 260px;
            object-fit: cover;
            display: block;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var section = document.querySelector('[data-impact-stats-counter-section]');
            if (!section) return;
            var els = section.querySelectorAll('[data-impact-counter-target]');
            if (!els.length) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                els.forEach(function (el) {
                    var fin = el.getAttribute('data-impact-counter-final');
                    if (fin !== null) el.textContent = fin;
                });
                return;
            }
            function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }
            function animateOne(el, durationMs) {
                var target = parseInt(el.getAttribute('data-impact-counter-target'), 10);
                var finalText = el.getAttribute('data-impact-counter-final') || '';
                if (isNaN(target) || target <= 0) { el.textContent = finalText; return; }
                var start = null;
                function frame(ts) {
                    if (start === null) start = ts;
                    var p = Math.min(1, (ts - start) / durationMs);
                    el.textContent = Math.round(target * easeOutQuart(p)).toLocaleString();
                    if (p < 1) requestAnimationFrame(frame);
                    else el.textContent = finalText;
                }
                requestAnimationFrame(frame);
            }
            var started = false;
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || started) return;
                    started = true;
                    io.disconnect();
                    els.forEach(function (el, i) {
                        setTimeout(function () { animateOne(el, 1900); }, i * 90);
                    });
                });
            }, { threshold: 0.22 });
            io.observe(section);
        });
    </script>

@endsection
