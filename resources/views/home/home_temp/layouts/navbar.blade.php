<header class="site-navbar" id="siteNavbar">
    <div class="site-navbar__container">

        <a href="{{ url('/') }}" class="site-navbar__brand" aria-label="Empatra DigiTech - Home">
            {{-- Assumption: logo lives at this path in the existing asset structure. Update if different. --}}
            <img src="{{ asset('images/logo.png') }}" alt="Empatra DigiTech" class="site-navbar__logo">
        </a>

        <button type="button"
                class="site-navbar__toggle"
                id="siteNavbarToggle"
                aria-expanded="false"
                aria-controls="siteNavbarNav"
                aria-label="Toggle navigation menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="site-navbar__nav" id="siteNavbarNav" aria-label="Main navigation">
            <ul class="site-navbar__links">
                <li><a href="#" class="is-active">Beranda</a></li>
                <li><a href="#">Layanan</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Paket Harga</a></li>
                <li><a href="#">Proses</a></li>
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Kontak</a></li>
            </ul>

            <div class="site-navbar__actions">
                <a href="https://wa.me/6280000000000"
                   target="_blank"
                   rel="noopener"
                   class="site-navbar__cta">
                    Konsultasi Gratis
                    <span class="site-navbar__cta-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 3.5c-4.7 0-8.5 3.8-8.5 8.5 0 1.6.44 3.1 1.2 4.38L3.5 20.5l4.24-1.15A8.46 8.46 0 0 0 12 20.5c4.7 0 8.5-3.8 8.5-8.5s-3.8-8.5-8.5-8.5Z" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M9 8.6c.15-.32.34-.33.53-.33h.42c.14 0 .32 0 .5.36.19.42.62 1.44.68 1.54.06.1.1.23.02.37-.08.15-.12.24-.24.37-.12.14-.25.3-.36.4-.12.12-.24.24-.1.48.13.24.6 1 1.28 1.62.88.8 1.62 1.05 1.86 1.17.24.12.38.1.53-.06.15-.16.62-.72.78-.97.17-.24.33-.2.55-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.29.06.1.06.6-.14 1.18-.2.58-1.17 1.11-1.63 1.18-.42.07-.94.1-1.5-.08-.34-.11-.79-.26-1.35-.5-2.38-1.03-3.93-3.44-4.05-3.6-.12-.16-.98-1.3-.98-2.48 0-1.18.62-1.76.84-2Z" fill="currentColor"/>
                        </svg>
                    </span>
                </a>
            </div>
        </nav>

    </div>
</header>

<style>
    .site-navbar,
    .site-navbar *,
    .site-navbar *::before,
    .site-navbar *::after {
        box-sizing: border-box;
    }

    .site-navbar {
        --enav-navy: #0a2a52;
        --enav-navy-soft: rgba(255, 255, 255, 0.35);
        --enav-red: #a6303f;
        /* Assumes a light hero behind the navbar at the top of the page (matches the reference).
           If the hero is a dark image/color instead, change this to #ffffff for contrast. */
        --enav-text-top: #16305a;
        --enav-text-scrolled: #ffffff;
        --enav-height: 84px;
        --enav-height-mobile: 64px;

        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        background-color: transparent;
        box-shadow: none;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        transition: background-color 0.35s ease, box-shadow 0.35s ease;
    }

    .site-navbar.is-scrolled {
        background-color: var(--enav-navy);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .site-navbar__container {
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        min-height: var(--enav-height);
        padding: 0 32px;
    }

    /* Brand */
    .site-navbar__brand {
        display: inline-flex;
        align-items: center;
        align-self: center;
        flex-shrink: 0;
        padding: 4px;
        border-radius: 8px;
        background-color: transparent;
        transition: background-color 0.35s ease;
    }

    /* The logo mark uses navy tones, so give it a light backing once the bar itself turns navy. */
    .site-navbar.is-scrolled .site-navbar__brand {
        background-color: rgba(255, 255, 255, 0.94);
    }

    .site-navbar__logo {
        height: 42px;
        width: auto;
        display: block;
    }

    /* Nav wrapper (links + CTA) */
    .site-navbar__nav {
        display: flex;
        align-items: stretch;
        align-self: stretch;
    }

    .site-navbar__links {
        display: flex;
        align-items: center;
        gap: 36px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .site-navbar__links a {
        position: relative;
        display: inline-block;
        padding: 6px 0;
        color: var(--enav-text-top);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: color 0.35s ease;
    }

    .site-navbar.is-scrolled .site-navbar__links a {
        color: var(--enav-text-scrolled);
    }

    .site-navbar__links a.is-active::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -6px;
        height: 2px;
        background-color: var(--enav-red);
    }

    .site-navbar__links a:hover {
        opacity: 0.75;
    }

    /* CTA zone: stays navy in both navbar states, as in the reference */
    .site-navbar__actions {
        display: flex;
        align-items: center;
        margin-left: 40px;
        padding: 0 28px;
        background-color: var(--enav-navy);
    }

    .site-navbar__cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border: 1.5px solid var(--enav-navy-soft);
        border-radius: 8px;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .site-navbar__cta:hover {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.6);
    }

    .site-navbar__cta-icon {
        display: inline-flex;
        width: 18px;
        height: 18px;
    }

    .site-navbar__cta-icon svg {
        width: 100%;
        height: 100%;
    }

    /* Mobile toggle (hidden on desktop) */
    .site-navbar__toggle {
        display: none;
        flex-direction: column;
        justify-content: space-between;
        align-self: center;
        width: 24px;
        height: 18px;
        padding: 0;
        border: 0;
        background: transparent;
        cursor: pointer;
    }

    .site-navbar__toggle span {
        display: block;
        height: 2px;
        width: 100%;
        background-color: var(--enav-text-top);
        border-radius: 1px;
        transition: background-color 0.35s ease, transform 0.25s ease, opacity 0.25s ease;
    }

    .site-navbar.is-scrolled .site-navbar__toggle span {
        background-color: var(--enav-text-scrolled);
    }

    .site-navbar.is-menu-open .site-navbar__toggle span:nth-child(1) {
        transform: translateY(8px) rotate(45deg);
    }

    .site-navbar.is-menu-open .site-navbar__toggle span:nth-child(2) {
        opacity: 0;
    }

    .site-navbar.is-menu-open .site-navbar__toggle span:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .site-navbar__container {
            min-height: var(--enav-height-mobile);
            padding: 0 20px;
            flex-wrap: wrap;
        }

        .site-navbar__toggle {
            display: flex;
        }

        .site-navbar__nav {
            order: 3;
            flex-basis: 100%;
            flex-direction: column;
            align-items: stretch;
            height: auto;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.3s ease, opacity 0.25s ease;
        }

        .site-navbar.is-menu-open .site-navbar__nav {
            max-height: 600px;
            opacity: 1;
        }

        /* Once scrolled or open on mobile, the bar is no longer transparent so the menu stays readable */
        .site-navbar.is-menu-open {
            background-color: var(--enav-navy);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .site-navbar.is-menu-open .site-navbar__links a {
            color: var(--enav-text-scrolled);
        }

        .site-navbar__links {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
            padding: 16px 0 8px;
        }

        .site-navbar__links a {
            display: block;
            width: 100%;
            padding: 10px 0;
        }

        .site-navbar__links a.is-active::after {
            bottom: 2px;
        }

        .site-navbar__actions {
            margin: 8px 0 20px;
            padding: 0;
            background-color: transparent;
        }

        .site-navbar__cta {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
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
</script>