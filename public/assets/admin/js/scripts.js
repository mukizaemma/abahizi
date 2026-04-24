/*!
    * Start Bootstrap - SB Admin v7.0.4 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2021 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

function sbAdminBindSidebarToggle() {
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (!sidebarToggle) {
        return;
    }
    sidebarToggle.onclick = function (e) {
        e.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        try {
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        } catch (err) { /* ignore */ }
    };
}

window.addEventListener('DOMContentLoaded', () => {
    sbAdminBindSidebarToggle();
});

document.addEventListener('turbo:load', () => {
    sbAdminBindSidebarToggle();
});
