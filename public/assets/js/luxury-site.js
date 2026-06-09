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
        if (!('loading' in HTMLImageElement.prototype)) {
            return;
        }
        document.querySelectorAll('img[data-src]').forEach(function (img) {
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
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
                var offset = (progress - 0.5) * 90;
                layer.style.transform = 'translate3d(0, ' + offset.toFixed(2) + 'px, 0) scale(1.08)';
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

    document.addEventListener('DOMContentLoaded', function () {
        initCounters('[data-lux-counter-section]', '[data-lux-counter-target]');
        initJourneySlider();
        initLazyImages();
        initHeroSlides();
        initParallaxSections();

        var heroVideo = document.querySelector('.lux-hero__video');
        if (heroVideo) {
            heroVideo.addEventListener('canplay', function () {
                heroVideo.classList.add('is-ready');
            }, { once: true });
        }
    });
})();
