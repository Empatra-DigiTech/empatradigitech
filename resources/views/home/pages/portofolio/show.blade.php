@extends('home.layouts.master')
@section("title", $result->title . " | EMPATRA DIGITECH")

@section('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('assets/css/portfolio/show.css') }}" rel="stylesheet">
    <style>
        .attachment__caption {
            display: none !important;
        }

        /* Ensure images are responsive */
        .article-body img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection

@section('content')

<!-- Full Width Featured Image Banner -->
<div class="featured-banner">
    <img src="{{ asset('storage/' . $result->image) }}"
         class="banner-image"
         alt="{{ $result->title }}">
    <div class="banner-overlay"></div>
</div>

<div class="portofolio-detail-container">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12 col-lg-9">
                <article class="article-container">

                    <!-- Article Content -->
                    <div class="article-content">
                        <!-- Article Header -->
                        <div class="article-header">
                            @if($result->industry)
                                <span class="case-study-kicker">Case Study — {{ $result->industry }}</span>
                            @else
                                <span class="case-study-kicker">Case Study</span>
                            @endif
                            <h1 class="article-title">{{ $result->title }}</h1>
                        </div>

                        <!-- Meta Bar: Client / Industry / Service / Brand -->
                        @if($result->klien || $result->industry || $result->layanan || $result->brand)
                        <div class="case-meta-bar">
                            @if($result->klien)
                            <div class="case-meta-item">
                                <span class="case-meta-label"><i class='bx bx-buildings'></i> Client</span>
                                <span class="case-meta-value">{{ $result->klien }}</span>
                            </div>
                            @endif
                            @if($result->industry)
                            <div class="case-meta-item">
                                <span class="case-meta-label"><i class='bx bx-briefcase'></i> Industry</span>
                                <span class="case-meta-value">{{ $result->industry }}</span>
                            </div>
                            @endif
                            @if($result->layanan)
                            <div class="case-meta-item">
                                <span class="case-meta-label"><i class='bx bx-cog'></i> Service</span>
                                <span class="case-meta-value">{{ $result->layanan }}</span>
                            </div>
                            @endif
                            @if($result->brand)
                            <div class="case-meta-item">
                                <span class="case-meta-label"><i class='bx bx-badge-check'></i> Brand</span>
                                <span class="case-meta-value">{{ $result->brand }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- CTA Buttons (top) -->
                        @php
                            $waDemoMessage = "Halo Empatra DigiTech, saya tertarik untuk membuat sistem serupa dengan proyek \"{$result->title}\", boleh minta info lebih lanjut?";
                            $waDemoLink = "https://wa.me/6285151811055?text=" . urlencode($waDemoMessage);
                        @endphp
                        <div class="case-cta-row">
                            @if($result->demo_url)
                            <a href="{{ $result->demo_url }}" target="_blank" rel="noopener" class="case-btn case-btn-outline">
                                <i class='bx bx-globe'></i>
                                Lihat Demo
                            </a>
                            @endif
                            <a href="{{ $waDemoLink }}" target="_blank" rel="noopener" class="case-btn case-btn-primary">
                                <i class='bx bxl-whatsapp'></i>
                                Buat Sistem Serupa
                            </a>
                        </div>

                        <!-- Overview (Main Trix Content) -->
                        <div class="article-body">
                            {!! $result->renderTrix("content") !!}
                        </div>

                        <!-- Tantangan Section (The Challenge) -->
                        @if($result->tantangan)
                        <div class="case-narrative-section case-challenge">
                            <div class="case-narrative-icon"><i class='bx bx-error-circle'></i></div>
                            <div class="case-narrative-body">
                                <span class="case-narrative-eyebrow">The Challenge</span>
                                <h2 class="section-title">Tantangan</h2>
                                <p>{!! nl2br(e($result->tantangan)) !!}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Solusi Section (The Solution) -->
                        @if($result->solusi)
                        <div class="case-narrative-section case-solution">
                            <div class="case-narrative-icon"><i class='bx bx-bulb'></i></div>
                            <div class="case-narrative-body">
                                <span class="case-narrative-eyebrow">The Solution</span>
                                <h2 class="section-title">Solusi</h2>
                                <p>{!! nl2br(e($result->solusi)) !!}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Fitur Section (Key Features) -->
                        @if($result->fitur)
                        <div class="case-features-section">
                            <span class="case-narrative-eyebrow">Key Features</span>
                            <h2 class="section-title">Fitur Utama</h2>
                            <ul class="case-features-list">
                                @foreach(preg_split('/\r\n|\r|\n/', trim($result->fitur)) as $fiturLine)
                                    @if(trim($fiturLine) !== '')
                                        <li>
                                            <i class='bx bx-check-circle'></i>
                                            <span>{{ trim($fiturLine, "- \t") }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Hasil Section (The Result) -->
                        @if($result->hasil)
                        <div class="case-result-section">
                            <div class="case-result-icon"><i class='bx bx-trending-up'></i></div>
                            <div class="case-result-body">
                                <span class="case-narrative-eyebrow case-narrative-eyebrow-light">The Result</span>
                                <h2 class="section-title case-result-title">Hasil &amp; Dampak</h2>
                                <p>{!! nl2br(e($result->hasil)) !!}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Gallery Section -->
                        @if($result->gallery && count($result->gallery) > 0)
                        <div class="gallery-section">
                            <h2 class="gallery-title">
                                <i class="bi bi-images"></i>
                                Galeri
                            </h2>
                            <div class="gallery-grid">
                                @foreach($result->gallery as $index => $image)
                                <div class="gallery-item" data-index="{{ $index }}">
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="Gallery Image {{ $index + 1 }}">
                                    <div class="gallery-overlay">
                                        <div class="gallery-zoom-icon">
                                            <i class="bi bi-zoom-in"></i>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- CTA Buttons (bottom) -->
                        <div class="case-cta-bottom">
                            <h3 class="case-cta-bottom-title">Tertarik dengan hasil seperti ini?</h3>
                            <p class="case-cta-bottom-subtitle">Mari diskusikan bagaimana kami bisa membantu proyek Anda berikutnya.</p>
                            <div class="case-cta-row case-cta-row-center">
                                @if($result->demo_url)
                                <a href="{{ $result->demo_url }}" target="_blank" rel="noopener" class="case-btn case-btn-outline">
                                    <i class='bx bx-globe'></i>
                                    Lihat Demo
                                </a>
                                @endif
                                <a href="{{ $waDemoLink }}" target="_blank" rel="noopener" class="case-btn case-btn-primary">
                                    <i class='bx bxl-whatsapp'></i>
                                    Buat Sistem Serupa
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-3">
                <div class="sidebar-container">
                    <h2 class="sidebar-title">Portfolio Lainnya</h2>

                    @forelse($except_result as $index => $row)
                        <article class="sidebar-news-card">
                            <a href="{{ route('home.portofolio.show', $row->id) }}" class="sidebar-news-link">
                                <div class="sidebar-news-image">
                                    <img src="{{ asset('storage/' . $row->image) }}"
                                         alt="{{ $row->title }}">
                                </div>
                                <div class="sidebar-news-content">
                                    <h3 class="sidebar-news-title">{{ $row->title }}</h3>
                                </div>
                            </a>
                        </article>
                    @empty
                        <div class="empty-sidebar">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada portfolio lainnya</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightbox-close">
            <i class="bi bi-x-lg"></i>
        </button>
        <button class="lightbox-nav lightbox-prev" id="lightbox-prev">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" id="lightbox-next">
            <i class="bi bi-chevron-right"></i>
        </button>
        <img src="" alt="Lightbox Image" class="lightbox-image" id="lightbox-image">
        <div class="lightbox-counter" id="lightbox-counter"></div>
    </div>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ========================================
        // Gallery Lightbox Functionality
        // ========================================
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxCounter = document.getElementById('lightbox-counter');

        let currentIndex = 0;
        let galleryImages = [];

        // Collect all gallery images
        galleryItems.forEach((item, index) => {
            const img = item.querySelector('img');
            galleryImages.push(img.src);

            // Click to open lightbox
            item.addEventListener('click', function() {
                currentIndex = index;
                openLightbox();
            });
        });

        function openLightbox() {
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
            updateLightboxImage();
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        function updateLightboxImage() {
            lightboxImage.src = galleryImages[currentIndex];
            lightboxCounter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;

            // Hide/show navigation buttons
            lightboxPrev.style.display = currentIndex === 0 ? 'none' : 'flex';
            lightboxNext.style.display = currentIndex === galleryImages.length - 1 ? 'none' : 'flex';
        }

        function nextImage() {
            if (currentIndex < galleryImages.length - 1) {
                currentIndex++;
                updateLightboxImage();
            }
        }

        function prevImage() {
            if (currentIndex > 0) {
                currentIndex--;
                updateLightboxImage();
            }
        }

        // Event listeners
        lightboxClose.addEventListener('click', closeLightbox);
        lightboxNext.addEventListener('click', nextImage);
        lightboxPrev.addEventListener('click', prevImage);

        // Close on background click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;

            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
        });


        // ========================================
        // Smooth Scroll for Anchor Links in Content
        // ========================================
        const contentLinks = document.querySelectorAll('.article-body a[href^="#"]');

        contentLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
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
            });
        });


        // ========================================
        // Auto-styling for article content
        // ========================================
        const articleBody = document.querySelector('.article-body');
        if (articleBody) {
            // Add class to all links
            const links = articleBody.querySelectorAll('a');
            links.forEach(link => {
                if (!link.querySelector('img')) { // Skip image links
                    link.style.color = '#B02E36';
                    link.style.textDecoration = 'underline';
                }
            });

            // Add responsive class to tables
            const tables = articleBody.querySelectorAll('table');
            tables.forEach(table => {
                const wrapper = document.createElement('div');
                wrapper.style.overflowX = 'auto';
                wrapper.style.marginBottom = '20px';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });
        }

    });
</script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
@endsection
