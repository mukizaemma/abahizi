(function () {
    'use strict';

    var intentUrl = document.querySelector('meta[name="form-channel-intent-url"]');
    if (!intentUrl) {
        return;
    }

    var intentEndpoint = intentUrl.getAttribute('content');
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function formTypeFor(form) {
        return form.getAttribute('data-form-type') || 'partnership';
    }

    function selectedChannel(form) {
        var checked = form.querySelector('.site-form-channel__radio:checked');
        return checked ? checked.value : '';
    }

    function channelBlock(form) {
        return form.querySelector('.site-form-channel');
    }

    function openButton(form) {
        return form.querySelector('.site-form-channel__open');
    }

    function confirmBlock(form) {
        return form.querySelector('.site-form-channel__confirm');
    }

    function formMessage(form, key, fallback) {
        if (!form) {
            return fallback;
        }
        return form.getAttribute(key) || fallback;
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
                confirmButtonText: 'OK',
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

    function isAutosave(form) {
        return form.getAttribute('data-form-autosave') === 'true';
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

    function openAutosave(form) {
        if (!validateNative(form)) {
            return;
        }

        var channel = selectedChannel(form);
        if (!channel) {
            return;
        }

        var hiddenChannel = form.querySelector('[name="submission_channel"]');
        if (hiddenChannel) {
            hiddenChannel.value = channel;
        }

        var btn = openButton(form);
        if (btn) {
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
        }

        notifyLoading(formMessage(form, 'data-msg-submitting', 'Submitting...'));

        var body = serializeForm(form);
        body.append('submission_channel', channel);

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
                        text: formMessage(form, 'data-msg-failed-text', 'Please try again.'),
                    });
                }

                var openUrl = result.json && result.json.open_url ? result.json.open_url : '';
                form.reset();
                updateOpenButton(form);
                syncDonateFields(form);

                var modalEl = form.closest('.modal');
                if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                    var modal = window.bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }

                return notify({
                    icon: 'success',
                    title: formMessage(form, 'data-msg-submitted', 'Submitted'),
                    text: formMessage(form, 'data-msg-submitted-text', 'Your request was sent.'),
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
                if (btn) {
                    btn.removeAttribute('aria-busy');
                    btn.disabled = !selectedChannel(form);
                }
            });
    }

    function updateOpenButton(form) {
        var btn = openButton(form);
        if (!btn) {
            return;
        }
        var channel = selectedChannel(form);
        btn.disabled = !channel;
        btn.textContent = 'Submit';
    }

    function resetChannelFlow(form) {
        var block = channelBlock(form);
        if (!block) {
            return;
        }
        var confirm = confirmBlock(form);
        if (confirm) {
            confirm.classList.add('d-none');
        }
        var openBtn = openButton(form);
        if (openBtn) {
            openBtn.classList.remove('d-none');
            openBtn.disabled = !selectedChannel(form);
        }
        form.querySelectorAll('.site-form-channel__radio').forEach(function (radio) {
            radio.disabled = false;
        });
        var fields = form.querySelectorAll('[name="submission_channel"], [name="channel_confirmed"], [name="channel_token"]');
        fields.forEach(function (field) {
            field.value = '';
        });
        var confirmBtn = form.querySelector('.site-form-channel__confirm-btn');
        if (confirmBtn) {
            confirmBtn.disabled = true;
        }
        form.querySelectorAll('input, textarea, select').forEach(function (el) {
            if (el.closest('.site-form-channel')) {
                return;
            }
            if (el.type === 'hidden' && (el.name === 'submission_channel' || el.name === 'channel_confirmed' || el.name === 'channel_token')) {
                return;
            }
            el.disabled = false;
        });
    }

    function serializeForm(form) {
        var data = new FormData(form);
        data.delete('submission_channel');
        data.delete('channel_confirmed');
        data.delete('channel_token');
        data.delete('submission_channel_choice');
        return data;
    }

    function validateNative(form) {
        if (typeof form.reportValidity === 'function') {
            return form.reportValidity();
        }
        return form.checkValidity();
    }

    function openChannel(form) {
        if (!validateNative(form)) {
            return;
        }

        var channel = selectedChannel(form);
        if (!channel) {
            return;
        }

        var btn = openButton(form);
        if (btn) {
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
        }

        var body = serializeForm(form);
        body.append('submission_channel', channel);
        body.append('form_type', formTypeFor(form));

        fetch(intentEndpoint, {
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
                });
            })
            .then(function (result) {
                if (!result.ok) {
                    notify({
                        icon: 'error',
                        title: formMessage(form, 'data-msg-failed', 'Not submitted'),
                        text: formMessage(form, 'data-msg-failed-text', 'Please try again.'),
                    });
                    resetChannelFlow(form);
                    return;
                }

                var openUrl = result.json.open_url;
                var token = result.json.token;
                if (!openUrl || !token) {
                    notify({
                        icon: 'error',
                        title: formMessage(form, 'data-msg-failed', 'Not submitted'),
                        text: formMessage(form, 'data-msg-failed-text', 'Please try again.'),
                    });
                    resetChannelFlow(form);
                    return;
                }

                window.open(openUrl, '_blank', 'noopener,noreferrer');

                var confirm = confirmBlock(form);
                if (confirm) {
                    confirm.classList.remove('d-none');
                }
                if (btn) {
                    btn.classList.add('d-none');
                }

                form.querySelector('[name="submission_channel"]').value = channel;
                form.querySelector('[name="channel_token"]').value = token;

                form.querySelectorAll('.site-form-channel__radio').forEach(function (radio) {
                    radio.disabled = true;
                });

                var confirmBtn = form.querySelector('.site-form-channel__confirm-btn');
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                }
            })
            .catch(function () {
                notify({
                    icon: 'error',
                    title: formMessage(form, 'data-msg-failed', 'Not submitted'),
                    text: formMessage(form, 'data-msg-failed-text', 'Please try again.'),
                });
                resetChannelFlow(form);
            })
            .finally(function () {
                if (btn) {
                    btn.removeAttribute('aria-busy');
                    if (!btn.classList.contains('d-none')) {
                        btn.disabled = !selectedChannel(form);
                    }
                }
            });
    }

    document.querySelectorAll('form.site-channel-form').forEach(function (form) {
        if (!channelBlock(form)) {
            return;
        }

        form.addEventListener('change', function (event) {
            if (event.target.classList.contains('site-form-channel__radio')) {
                updateOpenButton(form);
            }
            if (event.target.name === 'involvement_slug') {
                syncDonateFields(form);
            }
        });

        var openBtn = openButton(form);
        if (openBtn) {
            openBtn.addEventListener('click', function () {
                if (isAutosave(form)) {
                    openAutosave(form);
                    return;
                }
                openChannel(form);
            });
        }

        var retryBtn = form.querySelector('.site-form-channel__retry');
        if (retryBtn) {
            retryBtn.addEventListener('click', function () {
                resetChannelFlow(form);
            });
        }

        form.addEventListener('submit', function (event) {
            if (isAutosave(form)) {
                event.preventDefault();
                openAutosave(form);
                return;
            }

            var confirm = confirmBlock(form);
            if (!confirm || confirm.classList.contains('d-none')) {
                event.preventDefault();
                openChannel(form);
                return;
            }

            var confirmed = form.querySelector('[name="channel_confirmed"]');
            if (!confirmed || confirmed.value !== '1') {
                event.preventDefault();
                notify({
                    icon: 'info',
                    title: 'Not submitted',
                    text: 'Please try again.',
                });
                return;
            }
        });

        var confirmBtn = form.querySelector('.site-form-channel__confirm-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                var confirmed = form.querySelector('[name="channel_confirmed"]');
                if (confirmed) {
                    confirmed.value = '1';
                }
            });
        }

        updateOpenButton(form);
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
            updateOpenButton(form);
        });
    });
})();
