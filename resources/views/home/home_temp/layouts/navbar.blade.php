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
                <li><a href="#" class="is-active">Home</a></li>
                <li><a href="#">Layanan</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Inovasi</a></li>
                <li><a href="#">Informasi</a></li>

                <li class="site-navbar__item--dropdown">
                    <button type="button"
                            class="site-navbar__dropdown-toggle"
                            aria-expanded="false"
                            aria-haspopup="true"
                            aria-controls="siteNavbarDropdownMedia">
                        Media
                        <svg class="site-navbar__caret" viewBox="0 0 12 8" aria-hidden="true">
                            <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul class="site-navbar__dropdown" id="siteNavbarDropdownMedia">
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Galeri</a></li>
                    </ul>
                </li>

                <li class="site-navbar__item--dropdown">
                    <button type="button"
                            class="site-navbar__dropdown-toggle"
                            aria-expanded="false"
                            aria-haspopup="true"
                            aria-controls="siteNavbarDropdownTentang">
                        Tentang Kami
                        <svg class="site-navbar__caret" viewBox="0 0 12 8" aria-hidden="true">
                            <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul class="site-navbar__dropdown" id="siteNavbarDropdownTentang">
                        <li><a href="#">Visi &amp; Misi</a></li>
                        <li><a href="#">Struktur Organisasi</a></li>
                        <li><a href="#">Team</a></li>
                    </ul>
                </li>
                <li><a href="#">Kalkulator</a></li>
            </ul>
        </nav>

    </div>
</header>