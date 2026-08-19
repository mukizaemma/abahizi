(function () {
    'use strict';

    function toEmbedUrl(raw) {
        if (!raw) {
            return '';
        }
        var url = String(raw).trim();
        if (!url) {
            return '';
        }

        if (/\.(mp4|webm|ogg)(\?|$)/i.test(url) || url.indexOf('/storage/videos/') !== -1) {
            return { type: 'video', src: url };
        }

        var yt =
            url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/i) ||
            url.match(/[?&]v=([A-Za-z0-9_-]{6,})/i);
        if (yt && yt[1]) {
            return { type: 'iframe', src: 'https://www.youtube.com/embed/' + yt[1] + '?autoplay=1&rel=0' };
        }

        if (/vimeo\.com\/(\d+)/i.test(url)) {
            var id = url.match(/vimeo\.com\/(\d+)/i)[1];
            return { type: 'iframe', src: 'https://player.vimeo.com/video/' + id + '?autoplay=1' };
        }

        if (/youtube\.com\/embed\//i.test(url) || /player\.vimeo\.com\/video\//i.test(url)) {
            var sep = url.indexOf('?') === -1 ? '?' : '&';
            return { type: 'iframe', src: url + sep + 'autoplay=1' };
        }

        return { type: 'iframe', src: url };
    }

    function initReveal() {
        /* Handled site-wide in luxury-site.js */
    }

    function initCounters() {
        var section = document.querySelector('[data-lh-counter-section]');
        if (!section) {
            return;
        }
        var els = section.querySelectorAll('[data-lh-counter-target]');
        if (!els.length) {
            return;
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            els.forEach(function (el) {
                var fin = el.getAttribute('data-lh-counter-final');
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
            var target = parseInt(el.getAttribute('data-lh-counter-target'), 10);
            var finalText = el.getAttribute('data-lh-counter-final') || '';
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
                el.textContent = Math.round(target * easeOutQuart(p)).toLocaleString();
                if (p < 1) {
                    requestAnimationFrame(frame);
                } else {
                    el.textContent = finalText;
                }
            }
            requestAnimationFrame(frame);
        }

        var started = false;
        var io = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || started) {
                        return;
                    }
                    started = true;
                    io.disconnect();
                    els.forEach(function (el, i) {
                        window.setTimeout(function () {
                            animateOne(el, 1800);
                        }, i * 90);
                    });
                });
            },
            { threshold: 0.25, rootMargin: '0px 0px -8% 0px' }
        );

        io.observe(section);
    }

    function initHeroSlides() {
        var root = document.querySelector('[data-lh-hero-slides]');
        if (!root) {
            return;
        }
        var slides = root.querySelectorAll('[data-lh-hero-slide]');
        if (slides.length < 2) {
            return;
        }
        var index = 0;
        var interval = parseInt(root.getAttribute('data-lh-hero-interval') || '8000', 10);
        window.setInterval(function () {
            slides[index].classList.remove('is-active');
            slides[index].setAttribute('aria-hidden', 'true');
            index = (index + 1) % slides.length;
            slides[index].classList.add('is-active');
            slides[index].setAttribute('aria-hidden', 'false');
        }, interval);
    }

    function initVideoModal() {
        var modal = document.getElementById('lh-video-modal');
        if (!modal) {
            return;
        }
        var frame = modal.querySelector('[data-lh-video-frame]');
        var openers = document.querySelectorAll('[data-lh-video-open]');
        var closers = modal.querySelectorAll('[data-lh-video-close]');

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('hidden', 'hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('lh-modal-open');
            if (frame) {
                frame.innerHTML = '';
            }
        }

        function openModal(src) {
            var embed = toEmbedUrl(src);
            if (!embed || !frame) {
                return;
            }
            frame.innerHTML = '';
            if (embed.type === 'video') {
                var video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                video.playsInline = true;
                video.src = embed.src;
                frame.appendChild(video);
            } else {
                var iframe = document.createElement('iframe');
                iframe.src = embed.src;
                iframe.title = 'Story video';
                iframe.allow =
                    'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                iframe.allowFullscreen = true;
                frame.appendChild(iframe);
            }
            modal.removeAttribute('hidden');
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('lh-modal-open');
        }

        openers.forEach(function (btn) {
            btn.addEventListener('click', function () {
                openModal(btn.getAttribute('data-lh-video-src') || '');
            });
        });

        closers.forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });
    }

    function initProductLightbox() {
        var modal = document.getElementById('lh-product-lightbox');
        var dataNode = document.getElementById('lh-product-gallery-data');
        if (!modal || !dataNode) {
            return;
        }

        var items = [];
        try {
            items = JSON.parse(dataNode.textContent || '[]');
        } catch (err) {
            items = [];
        }
        if (!items.length) {
            return;
        }

        var img = modal.querySelector('[data-lh-gallery-image]');
        var titleEl = modal.querySelector('[data-lh-gallery-title]');
        var countEl = modal.querySelector('[data-lh-gallery-count]');
        var prevBtn = modal.querySelector('[data-lh-gallery-prev]');
        var nextBtn = modal.querySelector('[data-lh-gallery-next]');
        var openers = document.querySelectorAll('[data-lh-gallery-open]');
        var lastOpener = null;
        var current = 0;
        var touchStartX = 0;

        function setNavVisibility() {
            var show = items.length > 1;
            if (prevBtn) {
                prevBtn.hidden = !show;
            }
            if (nextBtn) {
                nextBtn.hidden = !show;
            }
        }

        function render() {
            var item = items[current];
            if (!item || !img) {
                return;
            }
            img.src = item.src || '';
            img.alt = item.title || '';
            if (titleEl) {
                titleEl.textContent = item.title || '';
            }
            if (countEl) {
                countEl.textContent = items.length > 1 ? current + 1 + ' / ' + items.length : '';
            }
            setNavVisibility();
        }

        function open(index, opener) {
            current = Math.max(0, Math.min(parseInt(index, 10) || 0, items.length - 1));
            lastOpener = opener || null;
            render();
            modal.removeAttribute('hidden');
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('lh-modal-open');
            var closeBtn = modal.querySelector('[data-lh-gallery-close]');
            if (closeBtn) {
                closeBtn.focus();
            }
        }

        function close() {
            if (!modal.classList.contains('is-open')) {
                return;
            }
            modal.classList.remove('is-open');
            modal.setAttribute('hidden', 'hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('lh-modal-open');
            if (img) {
                img.removeAttribute('src');
            }
            if (lastOpener && typeof lastOpener.focus === 'function') {
                lastOpener.focus();
            }
        }

        function go(step) {
            if (items.length < 2) {
                return;
            }
            current = (current + step + items.length) % items.length;
            render();
        }

        openers.forEach(function (btn) {
            btn.addEventListener('click', function () {
                open(btn.getAttribute('data-lh-gallery-index'), btn);
            });
        });

        modal.querySelectorAll('[data-lh-gallery-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                go(-1);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                go(1);
            });
        }

        document.addEventListener('keydown', function (e) {
            if (!modal.classList.contains('is-open')) {
                return;
            }
            if (e.key === 'Escape') {
                close();
            } else if (e.key === 'ArrowLeft') {
                go(-1);
            } else if (e.key === 'ArrowRight') {
                go(1);
            }
        });

        if (img) {
            img.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0] ? e.changedTouches[0].screenX : 0;
            }, { passive: true });
            img.addEventListener('touchend', function (e) {
                var endX = e.changedTouches[0] ? e.changedTouches[0].screenX : 0;
                var delta = endX - touchStartX;
                if (Math.abs(delta) < 40) {
                    return;
                }
                go(delta > 0 ? -1 : 1);
            }, { passive: true });
        }
    }

    function boot() {
        if (!document.body.classList.contains('landing-home')) {
            return;
        }
        initReveal();
        initCounters();
        initHeroSlides();
        initVideoModal();
        initProductLightbox();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
