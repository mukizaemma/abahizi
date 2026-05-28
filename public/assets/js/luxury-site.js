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

    document.addEventListener('DOMContentLoaded', function () {
        initCounters('[data-lux-counter-section]', '[data-lux-counter-target]');
        initJourneySlider();
        initLazyImages();

        var heroVideo = document.querySelector('.lux-hero__video');
        if (heroVideo) {
            heroVideo.addEventListener('canplay', function () {
                heroVideo.classList.add('is-ready');
            }, { once: true });
        }
    });
})();
