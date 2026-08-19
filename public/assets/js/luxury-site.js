(function () {
    'use strict';

    function initCounters(sectionSelector, valueSelector) {
        var section = document.querySelector(sectionSelector);
        if (!section) {
            return;
        }
        var els = section.querySelectorAll(valueSelector);
        if (!els.length) {
            return;
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            els.forEach(function (el) {
                var fin = el.getAttribute('data-lux-counter-final');
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
            var target = parseInt(el.getAttribute('data-lux-counter-target'), 10);
            var finalText = el.getAttribute('data-lux-counter-final') || '';
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
                var current = Math.round(target * easeOutQuart(p));
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
                els.forEach(function (el, i) {
                    window.setTimeout(function () {
                        animateOne(el, 1900);
                    }, i * 90);
                });
            });
        }, { threshold: 0.22, rootMargin: '0px 0px -8% 0px' });

        io.observe(section);
    }

    function initJourneySlider() {
        var track = document.querySelector('[data-lux-journey-slider]');
        if (!track) {
            return;
        }
        var cards = track.querySelectorAll('.lux-journey__card');
        if (cards.length < 2) {
            return;
        }
        var index = 0;
        cards.forEach(function (c, i) {
            c.style.display = i === 0 ? '' : 'none';
        });
        var prev = document.querySelector('[data-lux-journey-prev]');
        var next = document.querySelector('[data-lux-journey-next]');
        function show(i) {
            index = (i + cards.length) % cards.length;
            cards.forEach(function (c, j) {
                c.style.display = j === index ? '' : 'none';
            });
        }
        if (prev) {
            prev.addEventListener('click', function () {
                show(index - 1);
            });
        }
        if (next) {
            next.addEventListener('click', function () {
                show(index + 1);
            });
        }
    }

    function initLazyImages() {
        document.querySelectorAll('main img:not([loading])').forEach(function (img) {
            if (img.closest('.lh-hero, [data-lh-hero-slides], .tp-header-3__area, .site-header')) {
                return;
            }
            img.loading = 'lazy';
            img.decoding = 'async';
        });

        if (!('loading' in HTMLImageElement.prototype)) {
            return;
        }
        document.querySelectorAll('img[data-src]').forEach(function (img) {
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
        });
    }

    function shouldSkipReveal(el) {
        if (!el || el.nodeType !== 1) {
            return true;
        }
        if (el.closest('header, footer, nav, .tpoffcanvas-area, .scroll-top, .site-float-whatsapp, script, style, noscript')) {
            return true;
        }
        if (el.classList.contains('wow')) {
            return true;
        }
        if (el.classList.contains('lh-hero') || el.closest('.lh-hero')) {
            return true;
        }
        if (el.classList.contains('is-visible') || el.getAttribute('data-site-revealed') === '1') {
            return true;
        }
        if (el.closest('.lh-hero__content')) {
            return true;
        }
        return false;
    }

    function collectRevealNodes() {
        var nodes = [];
        var seen = typeof WeakSet === 'function' ? new WeakSet() : null;

        function add(el) {
            if (!el || shouldSkipReveal(el)) {
                return;
            }
            if (seen) {
                if (seen.has(el)) {
                    return;
                }
                seen.add(el);
            } else if (nodes.indexOf(el) !== -1) {
                return;
            }
            nodes.push(el);
        }

        document.querySelectorAll('.lh-reveal').forEach(add);

        document.querySelectorAll([
            'main section',
            'main .tp-about-4__area',
            'main .tp-blog-2__area',
            'main .tp-contact-form__area',
            'main .tp-team__area',
            'main .tp-gallery-3__area',
            'main .lh-impact',
            'main .lh-products',
            'main .lh-about',
            'main .lh-why',
            'main .lh-process',
            'main .lh-partners',
            'main .lh-own',
            'main .lh-contact',
            'main .lh-trust',
            'main .ho-layout',
            'main .ho-panel.is-active',
        ].join(',')).forEach(add);

        document.querySelectorAll([
            'main article',
            'main .card',
            'main .lh-product-card',
            'main .lh-impact__stat',
            'main .lh-process__step',
            'main .lh-why__item',
            'main .ho-card',
            'main .ho-guide-item',
            'main .ho-cred',
            'main .tp-blog-2__item',
            'main .tp-team__item',
            'main .team-page-card',
            'main .lux-journey__card',
            'main .media-gallery-card',
        ].join(',')).forEach(add);

        return nodes;
    }

    function playReveal(el, fromTop) {
        if (el.getAttribute('data-site-revealed') === '1') {
            return;
        }
        el.setAttribute('data-site-revealed', '1');
        el.classList.add('is-visible');

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || typeof el.animate !== 'function') {
            return;
        }

        el.animate(
            [
                {
                    opacity: 0,
                    transform: fromTop ? 'translate3d(0, -2.1rem, 0)' : 'translate3d(0, 2.4rem, 0)',
                },
                {
                    opacity: 1,
                    transform: 'translate3d(0, 0, 0)',
                },
            ],
            {
                duration: 720,
                delay: parseInt(el.getAttribute('data-reveal-stagger') || '0', 10),
                easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
                fill: 'both',
            }
        );
    }

    function initScrollReveal() {
        var nodes = collectRevealNodes();
        if (!nodes.length) {
            return;
        }

        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduced) {
            nodes.forEach(function (el) {
                el.setAttribute('data-site-revealed', '1');
                el.classList.add('is-visible');
            });
            return;
        }

        var io = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    var el = entry.target;
                    var fromTop = el.getAttribute('data-reveal-dir') === 'down';
                    playReveal(el, fromTop);
                    io.unobserve(el);
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
        );

        nodes.forEach(function (el, index) {
            var fromTop = index % 2 === 1;
            el.setAttribute('data-reveal-dir', fromTop ? 'down' : 'up');
            el.setAttribute('data-reveal-stagger', String((index % 5) * 60));

            var rect = el.getBoundingClientRect();
            var viewport = window.innerHeight || document.documentElement.clientHeight;
            if (rect.top < viewport * 0.88 && rect.bottom > 0) {
                el.setAttribute('data-site-revealed', '1');
                el.classList.add('is-visible');
                return;
            }
            io.observe(el);
        });
    }

    function initHeroSlides() {
        var hero = document.querySelector('[data-lux-hero-slides]');
        if (!hero) {
            return;
        }

        var slides = hero.querySelectorAll('[data-lux-hero-slide]');
        if (!slides.length) {
            return;
        }

        var captionEl = hero.querySelector('[data-lux-hero-caption]');
        var intervalMs = parseInt(hero.getAttribute('data-lux-hero-interval') || '9000', 10);
        var index = 0;
        var timer = null;
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function restartKenBurns(slideEl) {
            var ken = slideEl.querySelector('.lux-hero__kenburns');
            if (!ken || reducedMotion) {
                return;
            }
            ken.classList.remove('is-animating');
            void ken.offsetWidth;
            ken.classList.add('is-animating');
        }

        function updateCaption(text) {
            if (!captionEl || !text) {
                return;
            }
            var fallback = captionEl.getAttribute('data-fallback-caption') || '';
            var next = text.trim() !== '' ? text : fallback;
            if (captionEl.textContent.trim() === next) {
                return;
            }
            captionEl.classList.add('is-changing');
            window.setTimeout(function () {
                captionEl.textContent = next;
                captionEl.classList.remove('is-changing');
            }, reducedMotion ? 0 : 280);
        }

        function showSlide(nextIndex) {
            if (nextIndex === index || !slides[nextIndex]) {
                return;
            }

            slides[index].classList.remove('is-active');
            index = nextIndex;
            slides[index].classList.add('is-active');
            restartKenBurns(slides[index]);
            updateCaption(slides[index].getAttribute('data-caption') || '');
        }

        function nextSlide() {
            showSlide((index + 1) % slides.length);
        }

        restartKenBurns(slides[0]);

        if (slides.length > 1 && !reducedMotion) {
            timer = window.setInterval(nextSlide, intervalMs);
            hero.addEventListener('mouseenter', function () {
                if (timer) {
                    window.clearInterval(timer);
                    timer = null;
                }
            });
            hero.addEventListener('mouseleave', function () {
                if (!timer) {
                    timer = window.setInterval(nextSlide, intervalMs);
                }
            });
        }
    }

    function initParallaxSections() {
        var sections = document.querySelectorAll('[data-lux-parallax]');
        if (!sections.length) {
            return;
        }

        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reducedMotion) {
            return;
        }

        var ticking = false;

        function updateParallax() {
            sections.forEach(function (section) {
                var layer = section.querySelector('[data-lux-parallax-layer]');
                if (!layer) {
                    return;
                }

                var rect = section.getBoundingClientRect();
                var viewport = window.innerHeight || document.documentElement.clientHeight;
                if (rect.bottom < 0 || rect.top > viewport) {
                    return;
                }

                var progress = (viewport - rect.top) / (viewport + rect.height);
                var strong = section.getAttribute('data-lux-parallax-strength') === 'strong';
                var amplitude;
                var scale;
                if (strong) {
                    amplitude = window.innerWidth < 768 ? 110 : 220;
                    scale = window.innerWidth < 576 ? 1.32 : 1.22;
                } else {
                    amplitude = window.innerWidth < 768 ? 36 : 90;
                    scale = window.innerWidth < 576 ? 1.2 : 1.08;
                }
                var offset = (progress - 0.5) * amplitude;
                layer.style.transform = 'translate3d(0, ' + offset.toFixed(2) + 'px, 0) scale(' + scale + ')';
            });
            ticking = false;
        }

        function onScroll() {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(updateParallax);
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        updateParallax();
    }

    function initCopyLinks() {
        document.querySelectorAll('[data-copy-link]').forEach(function (btn) {
            if (btn.dataset.bound === '1') {
                return;
            }
            btn.dataset.bound = '1';
            btn.addEventListener('click', function () {
                var value = btn.getAttribute('data-copy-link') || window.location.href;
                var done = function () {
                    btn.classList.add('is-copied');
                    window.setTimeout(function () {
                        btn.classList.remove('is-copied');
                    }, 1600);
                };
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(value).then(done).catch(done);
                } else {
                    done();
                }
            });
        });
    }

    function initUpdatesMore() {
        document.querySelectorAll('[data-updates-toggle]').forEach(function (btn) {
            var selector = btn.getAttribute('data-updates-toggle');
            var panel = selector ? document.querySelector(selector) : null;
            if (!panel) {
                return;
            }
            var moreLabel = btn.querySelector('[data-updates-more-label]');
            var lessLabel = btn.querySelector('[data-updates-less-label]');

            btn.addEventListener('click', function () {
                var isOpen = !panel.hasAttribute('hidden');
                if (isOpen) {
                    panel.setAttribute('hidden', '');
                    panel.classList.remove('is-open');
                    btn.setAttribute('aria-expanded', 'false');
                    if (moreLabel) moreLabel.hidden = false;
                    if (lessLabel) lessLabel.hidden = true;
                } else {
                    panel.removeAttribute('hidden');
                    panel.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                    if (moreLabel) moreLabel.hidden = true;
                    if (lessLabel) lessLabel.hidden = false;
                }
            });
        });
    }

    function bootLuxurySite() {
        if (document.documentElement.getAttribute('data-luxury-booted') === '1') {
            return;
        }
        document.documentElement.setAttribute('data-luxury-booted', '1');
        initScrollReveal();
        initCounters('[data-lux-counter-section]', '[data-lux-counter-target]');
        initJourneySlider();
        initLazyImages();
        initHeroSlides();
        initParallaxSections();
        initUpdatesMore();
        initCopyLinks();

        var heroVideo = document.querySelector('.lux-hero__video');
        if (heroVideo) {
            heroVideo.addEventListener('canplay', function () {
                heroVideo.classList.add('is-ready');
            }, { once: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootLuxurySite);
    } else {
        bootLuxurySite();
    }
})();
