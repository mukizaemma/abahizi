(function () {
    'use strict';

    var MAX_BYTES = 700 * 1024;
    var pickerTarget = null;
    var pickerPage = 1;

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function libraryUrl() {
        var meta = document.querySelector('meta[name="media-library-url"]');
        return meta ? meta.getAttribute('content') : '/admin/media-library/json';
    }

    function usagesUrl() {
        var meta = document.querySelector('meta[name="media-usages-url"]');
        return meta ? meta.getAttribute('content') : '/admin/media-library/usages';
    }

    function replaceUrl() {
        var meta = document.querySelector('meta[name="media-replace-url"]');
        return meta ? meta.getAttribute('content') : '/admin/media-library/replace';
    }

    function destroyUrl() {
        var meta = document.querySelector('meta[name="media-destroy-url"]');
        return meta ? meta.getAttribute('content') : '/admin/media-library/destroy';
    }

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }

    function isImageInput(input) {
        if (!input || input.type !== 'file' || input.dataset.noMediaPicker === 'true') {
            return false;
        }
        var accept = (input.getAttribute('accept') || '').toLowerCase();
        var name = (input.getAttribute('name') || '').toLowerCase();
        if (accept.indexOf('pdf') !== -1) {
            return false;
        }
        return accept.indexOf('image') !== -1 || name.indexOf('image') !== -1 || name.indexOf('gallery') !== -1 || name.indexOf('logo') !== -1 || name.indexOf('poster') !== -1;
    }

    function loadImage(file) {
        return new Promise(function (resolve, reject) {
            var url = URL.createObjectURL(file);
            var img = new Image();
            img.onload = function () {
                URL.revokeObjectURL(url);
                resolve(img);
            };
            img.onerror = function () {
                URL.revokeObjectURL(url);
                reject(new Error('Could not read image'));
            };
            img.src = url;
        });
    }

    function canvasToBlob(canvas, type, quality) {
        return new Promise(function (resolve) {
            canvas.toBlob(function (blob) {
                resolve(blob);
            }, type, quality);
        });
    }

    async function compressToLimit(file) {
        if (file.size <= MAX_BYTES) {
            return { file: file, resized: false, originalSize: file.size };
        }
        if (!/^image\/(jpeg|jpg|png|webp)$/i.test(file.type)) {
            return { file: file, resized: false, originalSize: file.size };
        }

        var img = await loadImage(file);
        var type = file.type === 'image/png' ? 'image/jpeg' : (file.type || 'image/jpeg');
        var width = img.width;
        var height = img.height;
        var quality = 0.86;
        var blob = null;
        var attempts = 0;

        while (attempts < 12) {
            var canvas = document.createElement('canvas');
            canvas.width = Math.max(1, Math.round(width));
            canvas.height = Math.max(1, Math.round(height));
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            blob = await canvasToBlob(canvas, type, quality);
            if (blob && blob.size <= MAX_BYTES) {
                break;
            }
            if (quality > 0.5) {
                quality -= 0.08;
            } else {
                width *= 0.82;
                height *= 0.82;
                quality = 0.72;
            }
            attempts += 1;
        }

        var base = (file.name || 'image').replace(/\.[^.]+$/, '');
        var next = new File([blob], base + '.jpg', { type: type, lastModified: Date.now() });
        return { file: next, resized: true, originalSize: file.size };
    }

    function assignFiles(input, files, append) {
        var dt = new DataTransfer();
        if (append && input.files) {
            Array.prototype.forEach.call(input.files, function (existing) {
                dt.items.add(existing);
            });
        }
        files.forEach(function (file) {
            dt.items.add(file);
        });
        input.dataset.mediaInternal = '1';
        input.files = dt.files;
    }

    function renderPreview(wrap, files, notes) {
        var preview = wrap.querySelector('[data-media-preview]');
        var note = wrap.querySelector('[data-media-note]');
        if (!preview || !note) {
            return;
        }
        preview.innerHTML = '';
        Array.prototype.forEach.call(files, function (file) {
            var item = document.createElement('div');
            item.className = 'media-field__preview-item';
            var img = document.createElement('img');
            img.alt = file.name;
            img.src = URL.createObjectURL(file);
            var cap = document.createElement('span');
            cap.textContent = file.name + ' · ' + formatBytes(file.size);
            item.appendChild(img);
            item.appendChild(cap);
            preview.appendChild(item);
        });
        note.innerHTML = notes.join('<br>');
        note.hidden = notes.length === 0;
    }

    async function processFiles(input, fileList, append) {
        var wrap = input.closest('[data-media-field]');
        var incoming = Array.prototype.slice.call(fileList || []);
        if (!incoming.length) {
            return;
        }
        var processed = [];
        var notes = [];
        for (var i = 0; i < incoming.length; i += 1) {
            var result = await compressToLimit(incoming[i]);
            processed.push(result.file);
            if (result.resized) {
                notes.push(incoming[i].name + ' was ' + formatBytes(result.originalSize) + ' and is now ' + formatBytes(result.file.size) + ' (max 700 KB).');
            }
        }
        assignFiles(input, processed, append && input.multiple);
        input.dataset.mediaInternal = '0';
        if (wrap) {
            renderPreview(wrap, input.files, notes);
        }
    }

    function enhanceInput(input) {
        if (!isImageInput(input) || input.closest('[data-media-field]')) {
            return;
        }
        if (!input.getAttribute('accept')) {
            input.setAttribute('accept', 'image/*');
        }

        var wrap = document.createElement('div');
        wrap.className = 'media-field';
        wrap.setAttribute('data-media-field', 'true');
        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(input);

        var actions = document.createElement('div');
        actions.className = 'media-field__actions';
        actions.innerHTML = '<button type="button" class="btn btn-outline-secondary btn-sm" data-media-upload>Upload new</button>' +
            '<button type="button" class="btn btn-outline-primary btn-sm" data-media-choose>Choose existing</button>';
        wrap.insertBefore(actions, input);

        var preview = document.createElement('div');
        preview.className = 'media-field__preview';
        preview.setAttribute('data-media-preview', 'true');
        wrap.appendChild(preview);

        var note = document.createElement('div');
        note.className = 'media-field__note';
        note.setAttribute('data-media-note', 'true');
        note.hidden = true;
        wrap.appendChild(note);

        input.classList.add('media-field__input');

        actions.querySelector('[data-media-upload]').addEventListener('click', function () {
            input.click();
        });
        actions.querySelector('[data-media-choose]').addEventListener('click', function () {
            openPicker(input);
        });

        input.addEventListener('change', function (event) {
            if (input.dataset.mediaInternal === '1') {
                input.dataset.mediaInternal = '0';
                return;
            }
            var files = event.target.files;
            if (!files || !files.length) {
                return;
            }
            processFiles(input, files, false);
        });
    }

    function openPicker(input) {
        pickerTarget = input;
        pickerPage = 1;
        var modalEl = document.getElementById('mediaPickerModal');
        if (!modalEl || typeof bootstrap === 'undefined') {
            return;
        }
        loadPickerPage(1);
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    function loadPickerPage(page) {
        var grid = document.getElementById('mediaPickerGrid');
        var label = document.getElementById('mediaPickerPageLabel');
        var prev = document.getElementById('mediaPickerPrev');
        var next = document.getElementById('mediaPickerNext');
        if (!grid) {
            return;
        }
        grid.innerHTML = '<p class="text-muted mb-0">Loading images…</p>';
        fetch(libraryUrl() + '?page=' + page, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (payload) {
                pickerPage = payload.current_page;
                grid.innerHTML = '';
                if (!payload.data || !payload.data.length) {
                    grid.innerHTML = '<p class="text-muted mb-0">No images found yet.</p>';
                } else {
                    payload.data.forEach(function (item) {
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'media-picker-tile';
                        btn.innerHTML = '<img src="' + item.url + '" alt="' + item.name + '"><span>' + item.size_label + '</span>';
                        btn.addEventListener('click', function () {
                            selectLibraryImage(item);
                        });
                        grid.appendChild(btn);
                    });
                }
                if (label) {
                    label.textContent = 'Page ' + payload.current_page + ' of ' + payload.last_page + ' · ' + payload.total + ' images';
                }
                if (prev) {
                    prev.disabled = payload.current_page <= 1;
                }
                if (next) {
                    next.disabled = payload.current_page >= payload.last_page;
                }
            })
            .catch(function () {
                grid.innerHTML = '<p class="text-danger mb-0">Could not load the media library.</p>';
            });
    }

    function selectLibraryImage(item) {
        if (!pickerTarget) {
            return;
        }
        var form = pickerTarget.closest('form');
        var existing = form ? form.querySelector('input[name="existing_path"]') : null;
        if (existing) {
            existing.value = item.path;
            var wrap = pickerTarget.closest('[data-media-field]');
            if (wrap) {
                var preview = wrap.querySelector('[data-media-preview]');
                var note = wrap.querySelector('[data-media-note]');
                if (preview) {
                    preview.innerHTML = '<div class="media-field__preview-item"><img src="' + item.url + '" alt="' + item.name + '"><span>' + item.name + ' · ' + item.size_label + '</span></div>';
                }
                if (note) {
                    note.hidden = false;
                    note.textContent = 'Existing image selected and ready to use.';
                }
            }
            var modalEl = document.getElementById('mediaPickerModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
            return;
        }
        fetch(item.url)
            .then(function (res) { return res.blob(); })
            .then(function (blob) {
                var file = new File([blob], item.name, { type: blob.type || 'image/jpeg', lastModified: Date.now() });
                return processFiles(pickerTarget, [file], pickerTarget.multiple);
            })
            .then(function () {
                var modalEl = document.getElementById('mediaPickerModal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                }
            });
    }

    function inspectImage(path) {
        var body = document.getElementById('mediaInspectBody');
        var modalEl = document.getElementById('mediaInspectModal');
        if (!body || !modalEl) {
            return;
        }
        body.innerHTML = '<p class="text-muted mb-0">Loading…</p>';
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
        fetch(usagesUrl() + '?path=' + encodeURIComponent(path), { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (file) {
                renderInspect(file);
            })
            .catch(function () {
                body.innerHTML = '<p class="text-danger mb-0">Could not load image details.</p>';
            });
    }

    function renderInspect(file) {
        var body = document.getElementById('mediaInspectBody');
        var usages = file.usages || [];
        var usageHtml = '';
        if (!usages.length) {
            usageHtml = '<p class="text-muted">This file is not used anywhere. It is safe to remove.</p>';
        } else {
            usageHtml = '<p class="small text-muted">Tick the places that should get a new image. Leave a row unticked to skip it and keep the current image there.</p><div class="list-group mb-3">';
            usages.forEach(function (usage) {
                usageHtml += '<label class="list-group-item d-flex justify-content-between align-items-start gap-3">' +
                    '<span><input type="checkbox" name="usage_keys[]" value="' + usage.key + '" class="form-check-input me-2" checked> <strong>' + usage.label + '</strong><br><small class="text-muted">#' + usage.id + '</small></span>' +
                    (usage.edit_url ? '<a class="btn btn-sm btn-outline-secondary" href="' + usage.edit_url + '" target="_blank" rel="noopener">Open</a>' : '') +
                    '</label>';
            });
            usageHtml += '</div>';
        }

        body.innerHTML =
            '<div class="d-flex gap-3 mb-3">' +
                '<img src="' + file.url + '" alt="" class="media-inspect-preview">' +
                '<div>' +
                    '<div class="fw-semibold">' + file.name + '</div>' +
                    '<div class="small text-muted">' + file.size_label + (file.duplicate_count > 1 ? ' · ' + file.duplicate_count + ' copies in storage' : '') + '</div>' +
                    '<div class="small text-muted">' + file.path + '</div>' +
                '</div>' +
            '</div>' +
            usageHtml +
            (usages.length ? (
                '<form action="' + replaceUrl() + '" method="POST" enctype="multipart/form-data" class="border rounded p-3 mb-3" data-media-replace-form>' +
                    '<input type="hidden" name="_token" value="' + csrfToken() + '">' +
                    '<input type="hidden" name="path" value="' + file.path + '">' +
                    '<div data-media-usage-fields></div>' +
                    '<label class="form-label">Replacement image</label>' +
                    '<input type="file" name="image" class="form-control mb-2" accept="image/*">' +
                    '<input type="hidden" name="existing_path" value="">' +
                    '<p class="small text-muted mb-2">Upload a new picture or choose one already in the library, then save the change for the ticked uses.</p>' +
                    '<button type="submit" class="btn btn-primary btn-sm">Update selected uses</button>' +
                '</form>'
            ) : '') +
            '<form action="' + destroyUrl() + '" method="POST" onsubmit="return confirm(\'Remove this file from the system?\');">' +
                '<input type="hidden" name="_token" value="' + csrfToken() + '">' +
                '<input type="hidden" name="path" value="' + file.path + '">' +
                '<button type="submit" class="btn btn-outline-danger btn-sm"' + (usages.length ? ' disabled title="Change or skip remaining uses first"' : '') + '>Remove from system</button>' +
                (usages.length ? '<div class="small text-muted mt-2">Removal stays disabled until every remaining use is changed.</div>' : '') +
            '</form>';

        var replaceForm = body.querySelector('[data-media-replace-form]');
        if (replaceForm) {
            replaceForm.addEventListener('submit', function () {
                var holder = replaceForm.querySelector('[data-media-usage-fields]');
                holder.innerHTML = '';
                body.querySelectorAll('.list-group input[type="checkbox"]:checked').forEach(function (box) {
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'usage_keys[]';
                    hidden.value = box.value;
                    holder.appendChild(hidden);
                });
            });
            enhanceInput(replaceForm.querySelector('input[type="file"]'));
        }
    }

    function initMedia() {
        document.querySelectorAll('input[type="file"]').forEach(enhanceInput);

        var prev = document.getElementById('mediaPickerPrev');
        var next = document.getElementById('mediaPickerNext');
        if (prev && !prev.dataset.bound) {
            prev.dataset.bound = '1';
            prev.addEventListener('click', function () {
                if (pickerPage > 1) {
                    loadPickerPage(pickerPage - 1);
                }
            });
        }
        if (next && !next.dataset.bound) {
            next.dataset.bound = '1';
            next.addEventListener('click', function () {
                loadPickerPage(pickerPage + 1);
            });
        }

        document.querySelectorAll('[data-media-inspect]').forEach(function (btn) {
            if (btn.dataset.bound === '1') {
                return;
            }
            btn.dataset.bound = '1';
            btn.addEventListener('click', function () {
                inspectImage(btn.getAttribute('data-media-inspect'));
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initMedia);
    document.addEventListener('turbo:load', initMedia);
    document.addEventListener('shown.bs.modal', initMedia);

    function bindInitiativeWays(root) {
        if (!root || root.dataset.waysBound === '1') {
            return;
        }
        root.dataset.waysBound = '1';

        var rowsWrap = root.querySelector('[data-ways-rows]');
        var template = root.querySelector('[data-ways-template]');
        var sampleNode = root.querySelector('[data-ways-sample-json]');
        if (!rowsWrap || !template) {
            return;
        }

        function addRow(label, kind) {
            var html = template.innerHTML.trim();
            rowsWrap.insertAdjacentHTML('beforeend', html);
            var row = rowsWrap.lastElementChild;
            if (!row) {
                return;
            }
            var input = row.querySelector('input[name="way_label[]"]');
            var select = row.querySelector('select[name="way_kind[]"]');
            if (input && typeof label === 'string') {
                input.value = label;
            }
            if (select && kind) {
                select.value = kind;
            }
        }

        root.addEventListener('click', function (event) {
            var addBtn = event.target.closest('[data-ways-add]');
            if (addBtn && root.contains(addBtn)) {
                event.preventDefault();
                addRow('', 'standard');
                return;
            }

            var removeBtn = event.target.closest('[data-ways-remove]');
            if (removeBtn && root.contains(removeBtn)) {
                event.preventDefault();
                var row = removeBtn.closest('[data-ways-row]');
                if (row) {
                    row.remove();
                }
                if (!rowsWrap.querySelector('[data-ways-row]')) {
                    addRow('', 'standard');
                }
                return;
            }

            var sampleBtn = event.target.closest('[data-ways-samples]');
            if (sampleBtn && root.contains(sampleBtn)) {
                event.preventDefault();
                var samples = [];
                try {
                    samples = sampleNode ? JSON.parse(sampleNode.textContent || '[]') : [];
                } catch (e) {
                    samples = [];
                }
                rowsWrap.innerHTML = '';
                if (!samples.length) {
                    addRow('', 'standard');
                    return;
                }
                samples.forEach(function (way) {
                    addRow(way.label || '', way.kind === 'donate' ? 'donate' : 'standard');
                });
            }
        });
    }

    function initInitiativeWays() {
        document.querySelectorAll('[data-initiative-ways]').forEach(bindInitiativeWays);
    }

    document.addEventListener('DOMContentLoaded', initInitiativeWays);
    document.addEventListener('turbo:load', initInitiativeWays);
    document.addEventListener('shown.bs.modal', initInitiativeWays);
})();
