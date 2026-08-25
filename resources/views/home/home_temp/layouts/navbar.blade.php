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

<script>
    
</script>