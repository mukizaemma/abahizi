(function () {
    function uuid() {
        if (typeof crypto !== 'undefined' && crypto.randomUUID) {
            return crypto.randomUUID();
        }

        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (Math.random() * 16) | 0;
            var v = c === 'x' ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        });
    }

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function formatBytes(bytes) {
        if (bytes < 1024) {
            return bytes + ' B';
        }
        if (bytes < 1024 * 1024) {
            return (bytes / 1024).toFixed(1) + ' KB';
        }
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function initChunkedPdfUpload(root) {
        if (!root || root.dataset.chunkedPdfInit === '1') {
            return;
        }
        root.dataset.chunkedPdfInit = '1';

        var required = root.dataset.required === '1';
        var chunkUrl = root.dataset.chunkUrl;
        var finalizeUrl = root.dataset.finalizeUrl;
        var cancelUrl = root.dataset.cancelUrl;
        var chunkSize = parseInt(root.dataset.chunkSize, 10) || 1048576;

        var fileInput = root.querySelector('[data-chunked-pdf-file]');
        var hiddenInput = root.querySelector('[data-chunked-pdf-upload-id]');
        var progressWrap = root.querySelector('[data-chunked-pdf-progress]');
        var progressBar = root.querySelector('[data-chunked-pdf-progress-bar]');
        var statusEl = root.querySelector('[data-chunked-pdf-status]');
        var clearBtn = root.querySelector('[data-chunked-pdf-clear]');

        var currentUploadId = null;
        var uploading = false;
        var ready = !required;
        var submitButton = null;

        function setStatus(message, type) {
            if (!statusEl) {
                return;
            }
            statusEl.textContent = message || '';
            statusEl.className = 'small mt-1 text-' + (type || 'muted');
        }

        function setProgress(percent) {
            if (!progressWrap || !progressBar) {
                return;
            }
            progressWrap.classList.toggle('d-none', percent <= 0);
            progressBar.style.width = Math.min(100, Math.max(0, percent)) + '%';
            progressBar.setAttribute('aria-valuenow', String(percent));
        }

        function setSubmitEnabled(enabled) {
            if (submitButton) {
                submitButton.disabled = !enabled;
            }
        }

        function setReady(isReady, uploadId) {
            ready = isReady;
            if (hiddenInput) {
                hiddenInput.value = isReady ? uploadId || '' : '';
                hiddenInput.disabled = !isReady;
            }
            root.classList.toggle('chunked-pdf-ready', isReady);
            if (required) {
                setSubmitEnabled(isReady && !uploading);
            }
        }

        function formatError(payload, fallback) {
            if (payload && payload.message) {
                return payload.message;
            }
            if (payload && payload.errors) {
                var messages = [];
                Object.keys(payload.errors).forEach(function (key) {
                    var list = payload.errors[key];
                    if (Array.isArray(list)) {
                        messages = messages.concat(list);
                    }
                });
                if (messages.length) {
                    return messages.join(' ');
                }
            }
            return fallback || 'Upload failed.';
        }

        async function postForm(url, formData) {
            var response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: formData,
                credentials: 'same-origin',
            });

            var payload = {};
            try {
                payload = await response.json();
            } catch (e) {
                payload = {};
            }

            if (!response.ok) {
                throw new Error(formatError(payload, 'Upload failed (HTTP ' + response.status + ').'));
            }

            return payload;
        }

        async function cancelUpload(uploadId) {
            if (!uploadId || !cancelUrl) {
                return;
            }

            var formData = new FormData();
            formData.append('upload_id', uploadId);

            try {
                await postForm(cancelUrl, formData);
            } catch (e) {
                /* ignore cancel errors */
            }
        }

        async function uploadFile(file) {
            if (!file) {
                return;
            }

            if (!file.name.toLowerCase().endsWith('.pdf')) {
                setStatus('Please choose a PDF file.', 'danger');
                fileInput.value = '';
                return;
            }

            if (currentUploadId) {
                await cancelUpload(currentUploadId);
            }

            currentUploadId = uuid();
            uploading = true;
            setReady(false, null);
            setSubmitEnabled(false);
            setProgress(0);
            setStatus('Preparing upload…', 'muted');
            if (clearBtn) {
                clearBtn.classList.add('d-none');
            }

            var totalChunks = Math.max(1, Math.ceil(file.size / chunkSize));

            for (var index = 0; index < totalChunks; index++) {
                var start = index * chunkSize;
                var end = Math.min(start + chunkSize, file.size);
                var blob = file.slice(start, end);

                var formData = new FormData();
                formData.append('upload_id', currentUploadId);
                formData.append('chunk_index', String(index));
                formData.append('total_chunks', String(totalChunks));
                formData.append('original_name', file.name);
                formData.append('chunk', blob, 'chunk.bin');

                var result = await postForm(chunkUrl, formData);
                var percent = Math.round(((index + 1) / totalChunks) * 90);
                setProgress(percent);
                setStatus(
                    'Uploading… ' +
                        result.received +
                        ' / ' +
                        result.total_chunks +
                        ' (' +
                        formatBytes(file.size) +
                        ')',
                    'primary'
                );
            }

            setStatus('Finishing upload…', 'primary');
            setProgress(95);

            var finalizeData = new FormData();
            finalizeData.append('upload_id', currentUploadId);
            await postForm(finalizeUrl, finalizeData);

            uploading = false;
            setProgress(100);
            setReady(true, currentUploadId);
            setStatus('PDF ready: ' + file.name + ' — you can save the form now.', 'success');
            if (clearBtn) {
                clearBtn.classList.remove('d-none');
            }
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                var file = fileInput.files && fileInput.files[0];
                if (!file) {
                    return;
                }

                uploadFile(file).catch(function (err) {
                    uploading = false;
                    setProgress(0);
                    setReady(false, null);
                    setSubmitEnabled(false);
                    setStatus(err.message || 'Upload failed.', 'danger');
                    fileInput.value = '';
                    if (currentUploadId) {
                        cancelUpload(currentUploadId);
                        currentUploadId = null;
                    }
                });
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (currentUploadId) {
                    cancelUpload(currentUploadId);
                    currentUploadId = null;
                }
                if (fileInput) {
                    fileInput.value = '';
                }
                setReady(!required, null);
                setProgress(0);
                setStatus(required ? 'Upload a PDF to continue.' : 'No new PDF selected.', 'muted');
                clearBtn.classList.add('d-none');
            });
        }

        var form = root.closest('form');
        if (form) {
            submitButton = form.querySelector('[type="submit"]');
            form.addEventListener(
                'submit',
                function (event) {
                    if (uploading || (required && !ready)) {
                        event.preventDefault();
                        event.stopPropagation();
                        setStatus(
                            uploading
                                ? 'Please wait — PDF is still uploading.'
                                : 'Choose a PDF and wait until you see “PDF ready” before saving.',
                            'danger'
                        );
                    }
                },
                true
            );
        }

        if (required) {
            setSubmitEnabled(false);
            setStatus('Choose a PDF — it uploads in the background before you save.', 'muted');
        }
    }

    function initAll() {
        document.querySelectorAll('[data-chunked-pdf-upload]').forEach(initChunkedPdfUpload);
    }

    function bootChunkedPdfUpload() {
        initAll();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootChunkedPdfUpload);
    } else {
        bootChunkedPdfUpload();
    }

    document.addEventListener('turbo:load', bootChunkedPdfUpload);
})();
