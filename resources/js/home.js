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

document.querySelectorAll('.site-navbar__dropdown-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
        var item = toggle.closest('.site-navbar__item--dropdown');
        var isOpen = item.classList.contains('is-open');

        document
            .querySelectorAll('.site-navbar__item--dropdown.is-open')
            .forEach(function (openItem) {
                if (openItem !== item) {
                    openItem.classList.remove('is-open');
                    openItem
                        .querySelector('.site-navbar__dropdown-toggle')
                        .setAttribute('aria-expanded', 'false');
                }
            });

        item.classList.toggle('is-open', !isOpen);
        toggle.setAttribute('aria-expanded', String(!isOpen));
    });
});

document.addEventListener('click', function (e) {
    document.querySelectorAll('.site-navbar__item--dropdown.is-open').forEach(function (item) {
        if (!item.contains(e.target)) {
            item.classList.remove('is-open');
            item.querySelector('.site-navbar__dropdown-toggle').setAttribute(
                'aria-expanded',
                'false',
            );
        }
    });
});
