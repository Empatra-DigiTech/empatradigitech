// NAVBAR
(function () {
    var navbar = document.getElementById('siteNavbar');
    if (!navbar) {
        return;
    }
    var SCROLL_THRESHOLD = 40;
    var ticking = false;
    function updateScrollState() {
        navbar.classList.toggle('is-scrolled', window.scrollY > SCROLL_THRESHOLD);
        ticking = false;
    }
    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(updateScrollState);
            ticking = true;
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    updateScrollState(); // set correct state on load (e.g. page refreshed mid-scroll)
    // Mobile menu toggle
    var toggle = document.getElementById('siteNavbarToggle');
    var nav = document.getElementById('siteNavbarNav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var isOpen = navbar.classList.toggle('is-menu-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        nav.addEventListener('click', function (event) {
            if (event.target.tagName === 'A' && navbar.classList.contains('is-menu-open')) {
                navbar.classList.remove('is-menu-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
})();