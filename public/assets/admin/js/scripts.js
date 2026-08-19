/*!
    * Start Bootstrap - SB Admin v7.0.4 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2021 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */

function sbAdminIsDesktop() {
    return window.matchMedia('(min-width: 992px)').matches;
}

function sbAdminCloseMobileSidebar() {
    if (!sbAdminIsDesktop()) {
        document.body.classList.remove('sb-sidenav-toggled');
    }
}

function sbAdminBindSidebarToggle() {
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (!sidebarToggle) {
        return;
    }
    sidebarToggle.onclick = function (e) {
        e.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        if (sbAdminIsDesktop()) {
            try {
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            } catch (err) { /* ignore */ }
        }
    };

    const content = document.getElementById('layoutSidenav_content');
    if (content && content.dataset.overlayBound !== 'true') {
        content.dataset.overlayBound = 'true';
        content.addEventListener('click', function () {
            if (!sbAdminIsDesktop() && document.body.classList.contains('sb-sidenav-toggled')) {
                document.body.classList.remove('sb-sidenav-toggled');
            }
        });
    }

    document.querySelectorAll('#layoutSidenav_nav a.nav-link:not([data-bs-toggle])').forEach(function (link) {
        if (link.dataset.mobileCloseBound === 'true') {
            return;
        }
        link.dataset.mobileCloseBound = 'true';
        link.addEventListener('click', sbAdminCloseMobileSidebar);
    });
}

window.addEventListener('DOMContentLoaded', () => {
    sbAdminBindSidebarToggle();
});

document.addEventListener('turbo:load', () => {
    sbAdminBindSidebarToggle();
});

let sbAdminWasDesktop = window.matchMedia('(min-width: 992px)').matches;

window.addEventListener('resize', () => {
    const isDesktop = sbAdminIsDesktop();
    if (sbAdminWasDesktop !== isDesktop && !isDesktop) {
        document.body.classList.remove('sb-sidenav-toggled');
    }
    sbAdminWasDesktop = isDesktop;
});
