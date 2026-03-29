// script.js
function switchView(viewId) {
    document.querySelectorAll('.auth-view').forEach(view => {
        view.classList.remove('active');
    });
    const target = document.getElementById(viewId);
    if(target) {
        target.classList.add('active');
        if(viewId !== 'view-2fa') {
           target.reset();
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Only try to switch to view if we have views, but rely on PHP backend mostly
    const activeView = window.activeAuthView || 'view-login';
    if(document.getElementById(activeView)) {
        switchView(activeView);
    } else if(document.getElementById('view-login')) {
        switchView('view-login');
    }
});
