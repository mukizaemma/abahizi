@extends('layouts.frontbase')

@section('title', 'Our Impact')

@section('content')

    @include('frontend.includes.page-header', [
        'title' => 'Our Impact',
    ])

    @php
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

    <div class="tp-blog-2__area pt-90 pb-90">
        <div class="container">
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
                                        <img src="{{ asset('storage/' . ltrim($initiative->image, '/')) }}" alt="{{ $initiative->title }}" class="w-100 program-list-card__img">
                                    @else
                                        <div class="w-100 d-flex align-items-center justify-content-center program-list-card__img text-muted" style="background:#efefef;">
                                            No image
                                        </div>
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
        </div>

        @php
            $coreImpactStats = collect([
                ['label' => 'Families impacted', 'value' => $about->families_impacted ?? null],
                ['label' => 'Jobs created', 'value' => $about->jobs_created ?? null],
                ['label' => 'Hours of vocational training', 'value' => $about->training_hours ?? null],
            ])->filter(fn ($item) => trim((string) ($item['value'] ?? '')) !== '');

            $impactStatsBgFile = $about->core_values_background ?? $about->image2 ?? $about->image1 ?? $about->image ?? '';
            $impactStatsParallaxUrl = $impactStatsBgFile !== ''
                ? (str_starts_with((string) $impactStatsBgFile, 'http')
                    ? $impactStatsBgFile
                    : asset('storage/images/' . ltrim($impactStatsBgFile, '/')))
                : '';
        @endphp

        @if($coreImpactStats->isNotEmpty())
            <section
                class="impact-stats-hero mt-5 {{ $impactStatsParallaxUrl ? '' : 'impact-stats-hero--no-bg' }}"
                data-impact-stats-counter-section
                @if($impactStatsParallaxUrl)
                    style="--impact-stats-bg: url('{{ $impactStatsParallaxUrl }}');"
                @endif
            >
                <div class="impact-stats-hero__overlay">
                    <div class="container">
                        <div class="row g-4 g-lg-5 justify-content-center impact-stats-hero__grid">
                            @foreach($coreImpactStats as $stat)
                                @php
                                    $rawValue = trim((string) ($stat['value'] ?? ''));
                                    $digits = preg_replace('/[^\d]/', '', $rawValue);
                                    $counterTarget = $digits !== '' ? (int) $digits : 0;
                                @endphp
                                <div class="col-sm-6 col-lg-4">
                                    <article class="impact-stats-hero__stat">
                                        <p
                                            class="impact-stats-hero__value"
                                            data-impact-counter-target="{{ $counterTarget }}"
                                            data-impact-counter-final="{{ $rawValue }}"
                                        >{{ $counterTarget > 0 ? '0' : $rawValue }}</p>
                                        <p class="impact-stats-hero__label">{{ $stat['label'] }}</p>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <div class="container">
            @if(($impacts ?? collect())->isNotEmpty())
                <div class="row g-4 mb-5">
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

            <div class="row mt-2 mb-4">
                <div class="col-12 text-center">
                    <h3 class="tp-section-title mb-0">Testimonials</h3>
                </div>
            </div>

            @if(($testimonials ?? collect())->isEmpty())
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted mb-0">Testimonials will appear here once published.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($testimonials as $testimonial)
                        @php
                            $embedUrl = $youtubeEmbed($testimonial->video_url ?? null);
                            $coverUrl = !empty($testimonial->image)
                                ? asset('storage/' . ltrim($testimonial->image, '/'))
                                : null;
                            $fallbackCoverUrl = !$coverUrl && !empty($testimonial->image)
                                ? asset('storage/images/testimonies/' . ltrim($testimonial->image, '/'))
                                : null;
                            $coverUrl = $coverUrl ?? $fallbackCoverUrl;
                        @endphp
                        <div class="col-lg-6">
                            <article class="card border-0 shadow-sm h-100 impact-testimonial-card">
                                @if($coverUrl)
                                    <div class="impact-testimonial-card__thumb">
                                        <img src="{{ $coverUrl }}" alt="{{ $testimonial->names }}" class="w-100">
                                    </div>
                                @endif
                                <div class="card-body p-4">
                                    <h4 class="h5 mb-1">{{ $testimonial->names }}</h4>
                                    @if(!empty($testimonial->title))
                                        <p class="text-muted small mb-3">{{ $testimonial->title }}</p>
                                    @endif

                                    @if($embedUrl)
                                        <div class="ratio ratio-16x9 mb-3">
                                            <iframe src="{{ $embedUrl }}" title="Video testimonial by {{ $testimonial->names }}" allowfullscreen loading="lazy"></iframe>
                                        </div>
                                    @endif

                                    @if(!empty($testimonial->testimony))
                                        <div class="postbox__text mb-0 impact-testimonial-card__text">{!! $testimonial->testimony !!}</div>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        .impact-number-card {
            border-radius: 14px;
            overflow: hidden;
        }

        .impact-number-card__value {
            font-size: clamp(1.4rem, 3.8vw, 2rem);
            line-height: 1.15;
            font-weight: 800;
            color: var(--brand-secondary, #2c2c2c);
            letter-spacing: -0.02em;
        }

        .impact-number-card__label {
            font-size: 0.95rem;
            color: #5f6368;
            font-weight: 600;
        }

        .impact-number-card--item .impact-number-card__value {
            color: var(--brand-primary, #d8b400);
        }

        .impact-testimonial-card {
            border-radius: 14px;
            overflow: hidden;
        }

        .impact-testimonial-card__thumb {
            background: #f1f1f1;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .impact-testimonial-card__thumb img {
            height: 260px;
            object-fit: cover;
            display: block;
        }

        .impact-testimonial-card__text,
        .impact-testimonial-card__text p {
            margin-bottom: 0;
            font-size: 1rem;
            line-height: 1.75;
            color: #3a3a3a;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var section = document.querySelector('[data-impact-stats-counter-section]');
            if (!section) {
                return;
            }
            var els = section.querySelectorAll('[data-impact-counter-target]');
            if (!els.length) {
                return;
            }

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                els.forEach(function (el) {
                    var fin = el.getAttribute('data-impact-counter-final');
                    if (fin !== null) {
                        el.textContent = fin;
                    }
                });
                return;
            }

            function easeOutQuart(t) {
                return 1 - Math.pow(1 - t, 4);
            }

            function animateOne(el, durationMs) {
                var target = parseInt(el.getAttribute('data-impact-counter-target'), 10);
                var finalText = el.getAttribute('data-impact-counter-final') || '';
                if (isNaN(target) || target <= 0) {
                    el.textContent = finalText;
                    return;
                }
                var start = null;
                function frame(ts) {
                    if (start === null) {
                        start = ts;
                    }
                    var p = Math.min(1, (ts - start) / durationMs);
                    var eased = easeOutQuart(p);
                    var current = Math.round(target * eased);
                    el.textContent = current.toLocaleString();
                    if (p < 1) {
                        requestAnimationFrame(frame);
                    } else {
                        el.textContent = finalText;
                    }
                }
                requestAnimationFrame(frame);
            }

            var started = false;
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || started) {
                        return;
                    }
                    started = true;
                    io.disconnect();
                    var duration = 1900;
                    els.forEach(function (el, i) {
                        window.setTimeout(function () {
                            animateOne(el, duration);
                        }, i * 90);
                    });
                });
            }, { threshold: 0.22, rootMargin: '0px 0px -10% 0px' });

            io.observe(section);
        });
    </script>

@endsection
