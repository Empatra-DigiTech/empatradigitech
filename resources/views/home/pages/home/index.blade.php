@extends('home.layouts.master')
@section("title","Home | EMPATRA DIGITECH")

@section('css')
    <link href="assets/css/home/home.css" rel="stylesheet">
@endsection

@section('content')

<!-- Hero Swiper Section -->
<section class="hero-swiper-section swiper-container swiper-slider" data-loop="true" data-autoplay="5000" data-simulate-touch="false" data-slide-effect="fade">
    <div class="swiper-wrapper">
        @forelse ($table_banner as $index => $row)
            <div class="swiper-slide hero-slide" data-slide-bg="{{ asset('storage/' . $row->image) }}">
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-caption">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-xl-7">
                                <h1 class="hero-title" data-caption-animate="slideInUp" data-caption-delay="0">
                                    {{ $row->title }}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="swiper-slide hero-slide" data-slide-bg="https://i.pinimg.com/474x/80/c0/ec/80c0ec0db9f3db5bb0b4ebcf128797da.jpg">
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-caption">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-xl-7">
                                <h1 class="hero-title" data-caption-animate="slideInUp" data-caption-delay="0">
                                    EMPATRA DIGITECH
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Swiper Pagination -->
    <div class="swiper-pagination"></div>

    <!-- Swiper Navigation -->
    <div class="swiper-button-prev">
        <div class="swiper-button-arrow">
            <i class='bx bx-left-arrow-alt'></i>
        </div>
    </div>
    <div class="swiper-button-next">
        <div class="swiper-button-arrow">
            <i class='bx bx-right-arrow-alt'></i>
        </div>
    </div>
</section>


<!-- Layanan Section -->
<section id="layanan" class="layanan-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Layanan Kami</h2>
            <p class="section-subtitle">Solusi digital terbaik untuk kebutuhan bisnis Anda</p>
        </div>

        <!-- Layanan Cards -->
        <div class="layanan-grid">
            @forelse ($table_layanan as $index => $row)
                <div class="layanan-card" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                    <a href="{{ route('home.layanan.show', $row->id) }}" class="layanan-link">
                        <div class="layanan-image">
                            <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
                            <div class="layanan-overlay">
                                <span class="layanan-icon">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </span>
                            </div>
                        </div>
                        <div class="layanan-content">
                            <h3 class="layanan-title">{{ $row->title }}</h3>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-message">
                        <i class='bx bx-info-circle'></i>
                        <p>Tidak ada layanan tersedia saat ini</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paket Digital Section -->
        <div class="paket-digital-section">
            <div class="paket-header">
                <h2 class="paket-main-title">Paket Layanan Digital</h2>
                <p class="paket-main-subtitle">Pilih paket yang sesuai dengan kebutuhan bisnis Anda</p>
                
                <div class="paket-toggle-buttons">
                    <button type="button" class="btn-toggle active" id="btn-website" onclick="showPaket('website')">
                        <i class='bx bx-laptop'></i>
                        <span>Website Packages</span>
                    </button>
                    <button type="button" class="btn-toggle" id="btn-app" onclick="showPaket('app')">
                        <i class='bx bx-mobile-alt'></i>
                        <span>Mobile App Packages</span>
                    </button>
                </div>
            </div>

            <!-- Paket Website -->
            <div id="paket-website" class="paket-container active">
                <div class="pricing-grid">
                    @forelse ($paket_website ?? [] as $index => $paket)
                        <div class="pricing-column" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="pricing-card {{ $paket->is_recommended ? 'recommended' : '' }}">
                                @if($paket->is_recommended)
                                    <div class="recommended-badge">
                                        <span>Recommended</span>
                                    </div>
                                @endif

                                <div class="pricing-header">
                                    <h3 class="package-name">{{ $paket->nama_paket }}</h3>
                                    <div class="price-wrapper">
                                        <span class="currency">Rp</span>
                                        <span class="price">{{ number_format($paket->harga, 0, ',', '.') }}</span>
                                    </div>
                                    @if($paket->tagline)
                                        <p class="package-tagline">{{ $paket->tagline }}</p>
                                    @endif
                                </div>

                                <div class="pricing-body">
                                    <h4 class="features-heading">Features</h4>
                                    <ul class="features-list">
                                        @if(!empty($paket->fitur))
                                            @foreach(is_array($paket->fitur) ? $paket->fitur : json_decode($paket->fitur, true) as $fitur)
                                                <li>
                                                    <i class='bx bx-check-circle'></i>
                                                    <span>{{ $fitur }}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="pricing-footer">
                                    <a href="{{ route('home.kontak.index') }}?paket=website_{{ $paket->id }}" class="btn-purchase">
                                        Purchase Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-paket">
                            <i class='bx bx-package'></i>
                            <p>Website packages coming soon</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Paket Aplikasi -->
            <div id="paket-app" class="paket-container">
                <div class="pricing-grid">
                    @forelse ($paket_app ?? [] as $index => $paket)
                        <div class="pricing-column" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="pricing-card {{ $paket->is_recommended ? 'recommended' : '' }}">
                                @if($paket->is_recommended)
                                    <div class="recommended-badge">
                                        <span>Recommended</span>
                                    </div>
                                @endif

                                <div class="pricing-header">
                                    <h3 class="package-name">{{ $paket->nama_paket }}</h3>
                                    <div class="price-wrapper">
                                        <span class="currency">Rp</span>
                                        <span class="price">{{ number_format($paket->harga, 0, ',', '.') }}</span>
                                    </div>
                                    @if($paket->tagline)
                                        <p class="package-tagline">{{ $paket->tagline }}</p>
                                    @endif
                                </div>

                                <div class="pricing-body">
                                    <h4 class="features-heading">Features</h4>
                                    <ul class="features-list">
                                        @if(!empty($paket->fitur))
                                            @foreach(is_array($paket->fitur) ? $paket->fitur : json_decode($paket->fitur, true) as $fitur)
                                                <li>
                                                    <i class='bx bx-check-circle'></i>
                                                    <span>{{ $fitur }}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="pricing-footer">
                                    <a href="{{ route('home.kontak.index') }}?paket=app_{{ $paket->id }}" class="btn-purchase">
                                        Purchase Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-paket">
                            <i class='bx bx-mobile-alt'></i>
                            <p>Mobile app packages coming soon</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Featured News Section -->
<section id="featured-news" class="featured-news-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Berita Terpopuler</h2>
            <p class="section-subtitle">Informasi dan berita terkini dari kami</p>
        </div>

        @if($table_view != null)
            <div class="featured-news-main" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="featured-image">
                            <img src="{{ asset('storage/' . $table_view->image) }}" alt="{{ $table_view->title }}">
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="featured-content">
                            <div class="featured-meta">
                                <span class="meta-date">
                                    <i class='bx bx-calendar'></i>
                                    {{ Carbon\Carbon::parse($table_view->date)->translatedFormat('l, d F Y') }}
                                </span>
                                <span class="meta-views">
                                    <i class='bx bx-show'></i>
                                    {{ $count_view->total }} views
                                </span>
                            </div>
                            
                            <h3 class="featured-title">{{ $table_view->title }}</h3>
                            
                            <p class="featured-excerpt">
                                {!! Str::limit(strip_tags($table_view->renderTrix('content')), 200) !!}
                            </p>
                            
                            <a href="{{ route('home.berita.show', $table_view->id) }}" class="btn-read-featured">
                                Baca Selengkapnya
                                <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="no-featured-news">
                <p>Belum ada berita unggulan</p>
            </div>
        @endif

        <!-- Latest News Grid -->
        <div class="latest-news-grid">
            @forelse ($table_berita->sortByDesc('date')->slice(0, 3) as $index => $row)
                <div class="news-item" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                    <a href="{{ route('home.berita.show', $row->id) }}" class="news-item-link">
                        <div class="news-item-image">
                            <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
                            <div class="news-item-overlay">
                                <span class="overlay-icon">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="news-item-content">
                            <span class="news-date">
                                <i class='bx bx-calendar'></i>
                                {{ Carbon\Carbon::parse($row->date)->translatedFormat('d F Y') }}
                            </span>
                            <h4 class="news-item-title">{{ $row->title }}</h4>
                            <p class="news-item-excerpt">
                                {!! Str::limit(strip_tags($row->renderTrix('content')), 100) !!}
                            </p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-message">
                        <p>Tidak ada berita tersedia</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="text-center" data-aos="fade-up">
            <a href="{{ route('home.berita.index') }}" class="btn-view-all">
                Lihat Semua Berita
                <i class='bx bx-right-arrow-alt'></i>
            </a>
        </div>
    </div>
</section>


<!-- Calendar Section -->
<section id="calendar-section" class="calendar-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Kalender Kegiatan</h2>
            <p class="section-subtitle">Jadwal dan agenda kegiatan mendatang</p>
        </div>
        
        <div class="calendar-wrapper" data-aos="fade-up">
            @include('home.component.kalender')
        </div>
    </div>
</section>


<!-- Links Section -->
<section id="links-section" class="links-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Link Terkait</h2>
            <p class="section-subtitle">Tautan berguna dan informasi penting</p>
        </div>

        <div class="links-grid">
            @forelse ($table_tautan->chunk(5) as $chunkIndex => $chunk)
                <div class="links-column" data-aos="fade-up" data-aos-delay="{{ $chunkIndex * 100 }}">
                    <div class="links-card">
                        <ul class="links-list">
                            @foreach ($chunk as $row)
                                <li>
                                    <a href="{{ URL::to($row->url) }}" target="_blank" rel="noopener noreferrer">
                                        <i class='bx bx-link-external'></i>
                                        <span>{{ $row->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-message">
                        <p>Tidak ada tautan tersedia</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>


<!-- Contact Section -->
<section id="kontak" class="contact-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Hubungi Kami</h2>
            <p class="section-subtitle">Kami siap membantu kebutuhan digital Anda</p>
        </div>

        <!-- Contact Info Cards -->
        <div class="contact-info-grid">
            <div class="contact-info-card" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <h3 class="contact-info-title">Alamat</h3>
                <p class="contact-info-text">{{ $table_pengaturan->website_address }}</p>
            </div>

            <div class="contact-info-card" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-icon">
                    <i class="bi bi-telephone"></i>
                </div>
                <h3 class="contact-info-title">Telepon</h3>
                <p class="contact-info-text">{{ $table_pengaturan->website_phone }}</p>
            </div>

            <div class="contact-info-card" data-aos="fade-up" data-aos-delay="300">
                <div class="contact-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <h3 class="contact-info-title">Email</h3>
                <p class="contact-info-text">{{ $table_pengaturan->website_email }}</p>
            </div>
        </div>

        <!-- Map & Contact Form -->
        <div class="contact-main-grid">
            <div class="contact-map-wrapper" data-aos="fade-right">
                <iframe id="maps_big" src="{{ $table_pengaturan->website_map ?? '' }}" frameborder="0" loading="lazy" allowfullscreen></iframe>
            </div>

            <div class="contact-form-wrapper" data-aos="fade-left">
                <div class="contact-form-card">
                    <h3 class="form-title">Kirim Pesan</h3>
                    <form action="{{ route('home.kontak.store') }}" method="post" autocomplete="off" 
                          onsubmit="return confirm('Apakah anda yakin ingin mengirim pesan ini?')" 
                          enctype="multipart/form-data" class="contact-form">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-input" id="name" name="name" placeholder="Masukkan nama Anda" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-input" id="email" name="email" placeholder="Masukkan email Anda" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">Lampiran Gambar (Opsional)</label>
                            <input type="file" class="form-input" id="image" name="image" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-input" id="subject" name="subject" placeholder="Masukkan subjek pesan" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Pesan Anda</label>
                            <textarea class="form-textarea" id="message" name="message" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class='bx bx-send'></i>
                            <span>Kirim Pesan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/home/kalender.js"></script>
<script src="{{ URL::to('/') }}/assets/js/home/swiper/core.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/home/swiper/script.js"></script>

<script>
    // ========================================
    // Paket Toggle Functionality
    // ========================================
    function showPaket(type) {
        const containers = document.querySelectorAll('.paket-container');
        const buttons = document.querySelectorAll('.btn-toggle');
        
        // Fade out all containers
        containers.forEach(container => {
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            setTimeout(() => {
                container.classList.remove('active');
            }, 300);
        });
        
        // Remove active from all buttons
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Show selected container and activate button
        setTimeout(() => {
            if (type === 'website') {
                document.getElementById('paket-website').classList.add('active');
                document.getElementById('btn-website').classList.add('active');
            } else {
                document.getElementById('paket-app').classList.add('active');
                document.getElementById('btn-app').classList.add('active');
            }
            
            // Fade in selected container
            setTimeout(() => {
                const activeContainer = document.querySelector('.paket-container.active');
                activeContainer.style.opacity = '1';
                activeContainer.style.transform = 'translateY(0)';
            }, 50);
        }, 300);
    }
    
    
    // ========================================
    // Smooth Scroll for Anchor Links
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href !== '#' && href.length > 1) {
                    const targetElement = document.querySelector(href);
                    
                    if (targetElement) {
                        e.preventDefault();
                        
                        const header = document.getElementById('header');
                        const headerHeight = header ? header.offsetHeight : 0;
                        const targetPosition = targetElement.offsetTop - headerHeight - 20;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        
        // ========================================
        // Initialize Maps
        // ========================================
        const mapsBig = document.getElementById('maps_big');
        if (mapsBig && !mapsBig.src) {
            mapsBig.src = '{{ $table_pengaturan->website_map ?? "" }}';
        }
        
        const mapsMini = document.getElementById('maps_mini');
        if (mapsMini && !mapsMini.src) {
            mapsMini.src = '{{ $table_pengaturan->website_map ?? "" }}';
        }
    });
</script>
@endsection