(function () {
    'use strict';

    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function channelBlock(form) {
        return form.querySelector('.site-form-channel');
    }

    function formMessage(form, key, fallback) {
        if (form && form.getAttribute(key)) {
            return form.getAttribute(key);
        }
        var block = form ? channelBlock(form) : null;
        if (block && block.getAttribute(key)) {
            return block.getAttribute(key);
        }
        return fallback;
    }

    function brandConfirmColor() {
        return '#111111';
    }

    function notify(options) {
        if (window.Swal) {
            return window.Swal.fire({
                icon: options.icon || 'info',
                title: options.title || '',
                text: options.text || '',
                confirmButtonText: options.confirmButtonText || 'OK',
                confirmButtonColor: brandConfirmColor(),
            });
        }
        window.alert([options.title, options.text].filter(Boolean).join('\n'));
        return Promise.resolve();
    }

    function notifyLoading(title) {
        if (!window.Swal) {
            return;
        }
        window.Swal.fire({
            title: title || 'Submitting...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: function () {
                window.Swal.showLoading();
            },
        });
    }

    function closeNotify() {
        if (window.Swal && window.Swal.isVisible()) {
            window.Swal.close();
        }
    }

    function openExternal(url) {
        if (!url) {
            return;
        }
        var opened = window.open(url, '_blank', 'noopener,noreferrer');
        if (!opened) {
            window.location.href = url;
        }
    }

    function firstErrorText(json, fallback) {
        if (json && json.errors) {
            var keys = Object.keys(json.errors);
            if (keys.length) {
                var first = json.errors[keys[0]];
                if (Array.isArray(first) && first[0]) {
                    return first[0];
                }
                if (typeof first === 'string' && first) {
                    return first;
                }
            }
        }
        if (json && json.message && json.message !== 'The given data was invalid.') {
            return json.message;
        }
        return fallback;
    }

    function syncDonateFields(form) {
        var checked = form.querySelector('[name="involvement_slug"]:checked');
        var box = form.querySelector('[data-donate-fields]');
        if (!box) {
            return;
        }
        var isDonate = !!(checked && checked.getAttribute('data-kind') === 'donate');
        box.classList.toggle('d-none', !isDonate);
        var amount = box.querySelector('[name="donation_amount"]');
        if (amount) {
            amount.required = isDonate;
        }
        box.querySelectorAll('[name="donation_period"]').forEach(function (radio) {
            radio.required = isDonate;
        });
    }

    function serializeForm(form) {
        return new FormData(form);
    }

    function validateNative(form) {
        if (typeof form.reportValidity === 'function') {
            return form.reportValidity();
        }
        return form.checkValidity();
    }

    function setBusy(form, busy) {
        form.querySelectorAll('.site-form-channel__btn').forEach(function (btn) {
            btn.disabled = busy;
            if (busy) {
                btn.setAttribute('aria-busy', 'true');
            } else {
                btn.removeAttribute('aria-busy');
            }
        });
    }

    function submitChannel(form, channel) {
        if (!validateNative(form)) {
            return;
        }

        var hiddenChannel = form.querySelector('[name="submission_channel"]');
        if (hiddenChannel) {
            hiddenChannel.value = channel;
        }

        setBusy(form, true);
        notifyLoading(formMessage(form, 'data-msg-submitting', 'Submitting...'));

        var body = serializeForm(form);
        body.set('submission_channel', channel);

        fetch(form.getAttribute('action'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: body,
            credentials: 'same-origin',
        })
            .then(function (response) {
                return response.json().then(function (json) {
                    return { ok: response.ok, json: json };
                }).catch(function () {
                    return { ok: response.ok, json: {} };
                });
            })
            .then(function (result) {
                closeNotify();
                if (!result.ok) {
                    return notify({
                        icon: 'error',
                        title: formMessage(form, 'data-msg-failed', 'Not submitted'),
                        text: firstErrorText(result.json, formMessage(form, 'data-msg-failed-text', 'Please try again.')),
                    });
                }

                var openUrl = result.json && result.json.open_url ? result.json.open_url : '';
                form.reset();
                if (hiddenChannel) {
                    hiddenChannel.value = '';
                }
                syncDonateFields(form);

                var modalEl = form.closest('.modal');
                if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                    var modal = window.bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }

                var isWhatsApp = channel === 'whatsapp';
                var successText = isWhatsApp
                    ? formMessage(form, 'data-msg-submitted-whatsapp', 'Your request was saved. WhatsApp will open so you can send the message.')
                    : formMessage(form, 'data-msg-submitted-email', 'Your request was saved. Your email app will open so you can send the message.');
                var confirmLabel = isWhatsApp
                    ? formMessage(form, 'data-msg-open-whatsapp', 'Open WhatsApp')
                    : formMessage(form, 'data-msg-open-email', 'Open email');

                return notify({
                    icon: 'success',
                    title: formMessage(form, 'data-msg-submitted', 'Submitted'),
                    text: (result.json && result.json.message) ? result.json.message : successText,
                    confirmButtonText: confirmLabel,
                }).then(function () {
                    openExternal(openUrl);
                });
            })
            .catch(function () {
                closeNotify();
                notify({
                    icon: 'error',
                    title: formMessage(form, 'data-msg-failed', 'Not submitted'),
                    text: formMessage(form, 'data-msg-failed-text', 'Please try again.'),
                });
            })
            .finally(function () {
                setBusy(form, false);
            });
    }

    document.querySelectorAll('form.site-channel-form').forEach(function (form) {
        if (!channelBlock(form)) {
            return;
        }

        form.addEventListener('change', function (event) {
            if (event.target.name === 'involvement_slug') {
                syncDonateFields(form);
            }
        });

        form.querySelectorAll('.site-form-channel__btn[data-channel]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                submitChannel(form, btn.getAttribute('data-channel'));
            });
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
        });

        syncDonateFields(form);
    });

    document.querySelectorAll('.modal').forEach(function (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            var form = modal.querySelector('form.site-channel-form');
            if (!form) {
                return;
            }
            var started = form.querySelector('[name="started_at"]');
            if (started) {
                started.value = String(Math.floor(Date.now() / 1000));
            }
            syncDonateFields(form);
        });
    });
})();
