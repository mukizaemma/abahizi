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
                    var message = 'Unable to open the selected app. Please check your details and try again.';
                    if (result.json && result.json.errors) {
                        var errors = result.json.errors;
                        var firstKey = Object.keys(errors)[0];
                        if (firstKey && errors[firstKey][0]) {
                            message = errors[firstKey][0];
                        }
                    } else if (result.json && result.json.message) {
                        message = result.json.message;
                    }
                    window.alert(message);
                    resetChannelFlow(form);
                    return;
                }

                var openUrl = result.json.open_url;
                var token = result.json.token;
                if (!openUrl || !token) {
                    window.alert('Unable to prepare your message. Please try again.');
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
                window.alert('Network error. Please try again.');
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
        });

        var openBtn = openButton(form);
        if (openBtn) {
            openBtn.addEventListener('click', function () {
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
            var confirm = confirmBlock(form);
            if (!confirm || confirm.classList.contains('d-none')) {
                event.preventDefault();
                openChannel(form);
                return;
            }

            var confirmed = form.querySelector('[name="channel_confirmed"]');
            if (!confirmed || confirmed.value !== '1') {
                event.preventDefault();
                window.alert('Please send your message in the new tab, then click “I sent the message”.');
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
    });
})();
