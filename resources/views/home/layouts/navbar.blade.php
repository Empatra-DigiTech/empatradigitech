@php
    use App\Models\Menu;
@endphp

<header id="header" data-aos="fade-down" class="header d-flex align-items-center fixed-top">
    <div class="container container-xl position-relative d-flex align-items-center">

        <!-- Logo Section -->
        <a href="{{ route('home.index') }}" class="logo d-flex align-items-center me-auto">
            @if ($table_pengaturan->website_logo == null)
                <img src="{{ URL::to('/') }}/assets/img/favicon.png" alt="Company Logo">
            @else
                <img src="{{ asset('storage/' . $table_pengaturan->website_logo) }}" alt="Company Logo">
            @endif
        </a>

        <!-- Navigation Menu -->
        <nav id="navmenu" class="navmenu">
            <ul>
                @foreach ($table_menu as $index => $row)

                    @if ($row->title == 'Layanan')
                        <li><a href="{{ route('home.home.index') }}#layanan">{{ $row->title }}</a></li>

                    @elseif($row->title == 'Kontak')
                        <li><a href="{{ route('home.home.index') }}#kontak">{{ $row->title }}</a></li>

                    @elseif($row->title == 'FAQ')
                        {{-- moved into "Lainnya" dropdown below --}}

                    @elseif($row->title == 'Kalkulator')
                        {{-- moved into "Lainnya" dropdown below --}}

                    @elseif($row->title == 'Informasi Publik')
                        <!-- <li><a href="{{ route('home.home.informasi.index') }}"></a></li> -->

                    @elseif($row->title == 'Home')
                        <li><a href="{{ route('home.home.index') }}" class="active">{{ $row->title }}</a></li>

                    @elseif($row->title == 'Portofolio')
                        <li><a href="{{ route('home.home.portofolio.index') }}">{{ $row->title }}</a></li>

                    @elseif($row->title == 'Inovasi')
                        <li><a href="{{ route('home.home.inovasi.index') }}">{{ $row->title }}</a></li>

                    @elseif($row->title == 'Blog')
                        {{-- moved into "Lainnya" dropdown below --}}

                    @elseif($row->title == 'Galeri')
                        <li><a href="{{ route('home.home.galeri.index') }}">{{ $row->title }}</a></li>

                    @elseif($row->title == 'Profil')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <span>{{ $row->title }}</span>
                                <!-- <i class="bi bi-chevron-down toggle-dropdown"></i> -->
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('home.home.team.index') }}">Team</a></li>
                                {{-- <li><a href="{{ route('home.home.SO.index') }}">Struktur Organisasi</a></li> --}}
                                <li><a href="{{ route('home.home.VM.index') }}">Visi & Misi</a></li>
                            </ul>
                        </li>

                    @else
                        @if ($row->parent == null)
                            @php
                                $child = Menu::where('parent', $row->id)
                                    ->orderBy('created_at')
                                    ->get();
                            @endphp

                            @if ($child->count() == 0)
                                <li><a href="{{ '/' . strtolower($row->title) . '/show' }}">{{ $row->title }}</a></li>
                            @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle">
                                        <span>{{ $row->title }}</span>
                                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach ($child as $ch)
                                            <li><a href="{{ '/' . strtolower($ch->title) . '/show' }}">{{ $ch->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endif

                @endforeach

                <!-- Grouped secondary items to keep the navbar compact -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span>Lainnya</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('home.blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('home.home.index') }}#faq">FAQ</a></li>
                        <li><a href="{{ route('home.home.index') }}#pricing" class="nav-open-calculator-tab">Kalkulator</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Mobile Navigation Toggle -->
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('header');
        const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        const navmenu = document.querySelector('.navmenu');
        const dropdowns = document.querySelectorAll('.dropdown');

        // ========================================
        // Sticky Header on Scroll
        // ========================================
        function handleScroll() {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', handleScroll);


        // ========================================
        // Mobile Navigation Toggle
        // ========================================
        if (mobileNavToggle) {
            mobileNavToggle.addEventListener('click', function() {
                navmenu.classList.toggle('active');
                this.classList.toggle('bi-list');
                this.classList.toggle('bi-x');
            });
        }


        // ========================================
        // Dropdown Menu Functionality
        // ========================================
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Close other dropdowns
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('active');
                        }
                    });

                    // Toggle current dropdown
                    dropdown.classList.toggle('active');
                });
            }
        });


        // ========================================
        // Close Dropdown on Outside Click
        // ========================================
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });


        // ========================================
        // Active Link Highlighting
        // ========================================
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.navmenu a');

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });


        // ========================================
        // Smooth Scroll for Anchor Links
        // ========================================
        const anchorLinks = document.querySelectorAll('a[href^="#"]');

        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href !== '#' && href.includes('#')) {
                    const targetId = href.split('#')[1];
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        e.preventDefault();

                        // Close mobile menu if open
                        navmenu.classList.remove('active');
                        if (mobileNavToggle) {
                            mobileNavToggle.classList.add('bi-list');
                            mobileNavToggle.classList.remove('bi-x');
                        }

                        // Smooth scroll to target
                        const headerHeight = header.offsetHeight;
                        const targetPosition = targetElement.offsetTop - headerHeight - 20;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    });
</script>
