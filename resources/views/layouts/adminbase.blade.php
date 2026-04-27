<!DOCTYPE html>
<html lang="en">
    <head>
        @php
            $themeSetting = $setting ?? \App\Models\Setting::firstOrEmpty();
            $primary = $themeSetting->primary_color ?? '#fad200';
            $secondary = $themeSetting->secondary_color ?? '#2c2c2c';
            $neutral = $themeSetting->neutral_color ?? '#b0b0b0';
            $fontFamily = $themeSetting->font_family ?? 'Poppins';
            $googleFontParam = str_replace(' ', '+', $fontFamily);
        @endphp
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Dashboard') — Abahizi Rwanda</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ $googleFontParam }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/admin-refine.css') }}" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --brand-primary: {{ $primary }};
                --brand-secondary: {{ $secondary }};
                --brand-neutral: {{ $neutral }};
            }

            body {
                font-family: "{{ $fontFamily }}", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }
        </style>
        @stack('head')
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="{{ route('redirects') }}">Abahizi Rwanda</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" type="button" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>

            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="#" onsubmit="return false;">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Search…" aria-label="Search" autocomplete="off" />
                    <button class="btn btn-primary" type="button" disabled title="Search is not wired yet"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" id="adminUserDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw me-1"></i>
                        <span class="d-none d-lg-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}" target="_blank" rel="noopener">View site</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" data-no-sweet-submit="true">
                                @csrf
                                <button type="submit" class="dropdown-item">Log out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('assets/admin/js/scripts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/admin/js/datatables-simple-demo.js') }}"></script>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            function initAdminAlerts() {
                if (document.documentElement.hasAttribute('data-turbo-preview')) {
                    return;
                }

                const successMessage = @json(session('success'));
                const errorMessage = @json(session('error'));
                const warningMessage = @json(session('warning'));
                const hasValidationErrors = {{ $errors->any() ? 'true' : 'false' }};
                const pendingFeedback = sessionStorage.getItem('admin:pending-feedback') === '1';
                const pageKey = window.location.pathname + window.location.search;
                const successKey = successMessage ? ('success:' + pageKey + ':' + successMessage) : null;
                const errorKey = errorMessage ? ('error:' + pageKey + ':' + errorMessage) : null;
                const warningKey = warningMessage ? ('warning:' + pageKey + ':' + warningMessage) : null;
                const validationKey = 'validation:' + pageKey;

                if (successMessage && pendingFeedback && sessionStorage.getItem(successKey) !== 'shown') {
                    sessionStorage.setItem(successKey, 'shown');
                    sessionStorage.removeItem('admin:pending-feedback');
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: successMessage,
                        confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-primary') || '#fad200',
                    });
                } else if (errorMessage && pendingFeedback && sessionStorage.getItem(errorKey) !== 'shown') {
                    sessionStorage.setItem(errorKey, 'shown');
                    sessionStorage.removeItem('admin:pending-feedback');
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: errorMessage,
                    });
                } else if (warningMessage && pendingFeedback && sessionStorage.getItem(warningKey) !== 'shown') {
                    sessionStorage.setItem(warningKey, 'shown');
                    sessionStorage.removeItem('admin:pending-feedback');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Notice',
                        text: warningMessage,
                    });
                } else if (hasValidationErrors && sessionStorage.getItem(validationKey) !== 'shown') {
                    sessionStorage.setItem(validationKey, 'shown');
                    sessionStorage.removeItem('admin:pending-feedback');
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation error',
                        text: 'Please correct the highlighted fields and try again.',
                    });
                } else if (!successMessage && !errorMessage && !warningMessage) {
                    sessionStorage.removeItem('admin:pending-feedback');
                }
            }

            function initAdminSubmitFeedback() {
                document.querySelectorAll('form:not([data-no-sweet-submit])').forEach((form) => {
                    if (form.dataset.sweetBound === 'true') {
                        return;
                    }
                    form.dataset.sweetBound = 'true';
                    form.addEventListener('submit', () => {
                        sessionStorage.setItem('admin:pending-feedback', '1');
                        Swal.fire({
                            title: 'Submitting...',
                            text: 'Please wait while we save your changes.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                    });
                });
            }

            function initAdminSummernote() {
                if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.summernote === 'undefined') {
                    return;
                }
                window.jQuery('form textarea[data-editor="rich"]').each(function () {
                    const $el = window.jQuery(this);
                    // Skip summernote-generated/internal textareas
                    if ($el.hasClass('note-codable') || $el.hasClass('note-input') || $el.closest('.note-editor').length) {
                        return;
                    }
                    if ($el.hasClass('swal2-textarea') || $el.closest('.swal2-container').length) {
                        return;
                    }
                    if ($el.data('summernote-initialized') === true) {
                        return;
                    }
                    $el.summernote({
                        height: 220,
                        placeholder: 'Write content here...',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link', 'picture']],
                            ['view', ['codeview']]
                        ]
                    });
                    $el.data('summernote-initialized', true);
                });
            }

            function initAdminModalCloseControls() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    const header = modal.querySelector('.modal-header');
                    if (header && !header.querySelector('[data-bs-dismiss="modal"]')) {
                        const closeBtn = document.createElement('button');
                        closeBtn.type = 'button';
                        closeBtn.className = 'btn-close';
                        closeBtn.setAttribute('data-bs-dismiss', 'modal');
                        closeBtn.setAttribute('aria-label', 'Close');
                        header.appendChild(closeBtn);
                    }

                    const footer = modal.querySelector('.modal-footer');
                    if (footer && !footer.querySelector('[data-bs-dismiss="modal"]')) {
                        const footerCloseBtn = document.createElement('button');
                        footerCloseBtn.type = 'button';
                        footerCloseBtn.className = 'btn btn-outline-secondary';
                        footerCloseBtn.setAttribute('data-bs-dismiss', 'modal');
                        footerCloseBtn.textContent = 'Close';
                        footer.prepend(footerCloseBtn);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', initAdminAlerts);
            document.addEventListener('DOMContentLoaded', initAdminSubmitFeedback);
            document.addEventListener('DOMContentLoaded', initAdminSummernote);
            document.addEventListener('DOMContentLoaded', initAdminModalCloseControls);
            document.addEventListener('turbo:load', initAdminAlerts);
            document.addEventListener('turbo:load', initAdminSubmitFeedback);
            document.addEventListener('turbo:load', initAdminSummernote);
            document.addEventListener('turbo:load', initAdminModalCloseControls);
            document.addEventListener('shown.bs.tab', initAdminSummernote);
        </script>

        @yield('scripts')
        @stack('scripts')
    </body>
</html>
