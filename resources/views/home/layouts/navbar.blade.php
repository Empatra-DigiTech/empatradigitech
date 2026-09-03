@php
    use App\Models\Menu;

    // Titles already rendered as fixed nav sections above/below —
    // skipped here so admin-managed items aren't duplicated.
    $reservedMenuTitles = ['Home', 'Layanan', 'Portofolio', 'Inovasi', 'Informasi', 'Informasi Publik', 'Blog', 'Galeri', 'Profil', 'Kontak', 'FAQ', 'Kalkulator'];
    $customMenuItems = $table_menu->whereNull('parent')->whereNotIn('title', $reservedMenuTitles);
@endphp

<header class="site-navbar" id="siteNavbar">
    <div class="site-navbar__container">

        <a href="{{ url('/') }}" class="site-navbar__brand" aria-label="Empatra DigiTech - Home">
            @if ($table_pengaturan->website_logo == null)
                <img src="{{ URL::to('/') }}/assets/img/favicon.png" alt="Empatra DigiTech" class="site-navbar__logo">
            @else
                <img src="{{ asset('storage/' . $table_pengaturan->website_logo) }}" alt="Empatra DigiTech" class="site-navbar__logo">
            @endif
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
                <li><a href="{{ route('home.home.index') }}" class="{{ request()->routeIs('home.home.index') ? 'is-active' : '' }}">Home</a></li>
                <li><a href="{{ route('home.home.index') }}#layanan">Layanan</a></li>
                <li><a href="{{ route('home.portofolio.index') }}" class="{{ request()->routeIs('home.portofolio.*') ? 'is-active' : '' }}">Portfolio</a></li>
                <li><a href="{{ route('home.inovasi.index') }}" class="{{ request()->routeIs('home.inovasi.*') ? 'is-active' : '' }}">Inovasi</a></li>
                <li><a href="{{ route('home.informasi.index') }}" class="{{ request()->routeIs('home.informasi.*') ? 'is-active' : '' }}">Informasi</a></li>

                @foreach ($customMenuItems as $row)
                    @php
                        $children = Menu::where('parent', $row->id)->orderBy('created_at')->get();
                    @endphp

                    @if ($children->count() == 0)
                        <li><a href="{{ '/' . strtolower($row->title) . '/show' }}" class="{{ request()->is(strtolower($row->title) . '/show') ? 'is-active' : '' }}">{{ $row->title }}</a></li>
                    @else
                        @php
                            $childSlugs = $children->map(fn ($child) => strtolower($child->title) . '/show');
                            $groupActive = $childSlugs->contains(fn ($slug) => request()->is($slug));
                        @endphp
                        <li class="site-navbar__item--dropdown{{ $groupActive ? ' is-active' : '' }}">
                            <button type="button"
                                    class="site-navbar__dropdown-toggle"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                    aria-controls="siteNavbarDropdownMenu{{ $row->id }}">
                                {{ $row->title }}
                                <svg class="site-navbar__caret" viewBox="0 0 12 8" aria-hidden="true">
                                    <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <ul class="site-navbar__dropdown" id="siteNavbarDropdownMenu{{ $row->id }}">
                                @foreach ($children as $child)
                                    <li><a href="{{ '/' . strtolower($child->title) . '/show' }}" class="{{ request()->is(strtolower($child->title) . '/show') ? 'is-active' : '' }}">{{ $child->title }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach

                <li class="site-navbar__item--dropdown{{ request()->routeIs('home.blog.*') || request()->routeIs('home.galeri.*') ? ' is-active' : '' }}">
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
                        <li><a href="{{ route('home.blog.index') }}" class="{{ request()->routeIs('home.blog.*') ? 'is-active' : '' }}">Blog</a></li>
                        <li><a href="{{ route('home.galeri.index') }}" class="{{ request()->routeIs('home.galeri.*') ? 'is-active' : '' }}">Galeri</a></li>
                    </ul>
                </li>

                <li class="site-navbar__item--dropdown{{ request()->routeIs('home.VM.*') || request()->routeIs('home.SO.*') || request()->routeIs('home.team.*') ? ' is-active' : '' }}">
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
                        <li><a href="{{ route('home.VM.index') }}" class="{{ request()->routeIs('home.VM.*') ? 'is-active' : '' }}">Visi &amp; Misi</a></li>
                        <li><a href="{{ route('home.SO.index') }}" class="{{ request()->routeIs('home.SO.*') ? 'is-active' : '' }}">Struktur Organisasi</a></li>
                        <li><a href="{{ route('home.team.index') }}" class="{{ request()->routeIs('home.team.*') ? 'is-active' : '' }}">Team</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('home.home.index') }}#pricing" class="nav-open-calculator-tab">Kalkulator</a></li>
            </ul>
        </nav>

    </div>
</header>