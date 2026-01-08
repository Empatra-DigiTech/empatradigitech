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

<div class="berita-detail-container">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12 col-lg-9">
                <article class="article-container">

                    <!-- Article Content -->
                    <div class="article-content">
                        <!-- Article Header -->
                        <div class="article-header">
                            <h1 class="article-title">{{ $result->title }}</h1>
                            <div class="article-meta">
                                <span class="meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ Carbon\Carbon::parse($result->date)->translatedFormat('l, d F Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Info Section -->
                        @if($result->klien || $result->industry || $result->layanan || $result->brand)
                        <div class="info-section">
                            <h4>
                                <i class="bi bi-info-circle"></i>
                                Informasi Umum
                            </h4>
                            @if($result->klien)
                            <div class="info-item">
                                <span class="info-label">Klien:</span>
                                <span class="info-value">{{ $result->klien }}</span>
                            </div>
                            @endif
                            @if($result->industry)
                            <div class="info-item">
                                <span class="info-label">Industri:</span>
                                <span class="info-value">{{ $result->industry }}</span>
                            </div>
                            @endif
                            @if($result->layanan)
                            <div class="info-item">
                                <span class="info-label">Layanan:</span>
                                <span class="info-value">{{ $result->layanan }}</span>
                            </div>
                            @endif
                            @if($result->brand)
                            <div class="info-item">
                                <span class="info-label">Brand:</span>
                                <span class="info-value">{{ $result->brand }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Main Content -->
                        <div class="article-body">
                            {!! $result->renderTrix("content") !!}
                        </div>

                        <!-- Tantangan Section -->
                        @if($result->tantangan)
                        <div class="section-content">
                            <h2 class="section-title">Tantangan</h2>
                            <p>{!! nl2br(e($result->tantangan)) !!}</p>
                        </div>
                        @endif

                        <!-- Solusi Section -->
                        @if($result->solusi)
                        <div class="section-content">
                            <h2 class="section-title">Solusi</h2>
                            <p>{!! nl2br(e($result->solusi)) !!}</p>
                        </div>
                        @endif

                        <!-- Fitur Section -->
                        @if($result->fitur)
                        <div class="section-content">
                            <h2 class="section-title">Fitur</h2>
                            <p>{!! nl2br(e($result->fitur)) !!}</p>
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
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-3">
                <div class="sidebar-container">
                    <h2 class="sidebar-title">Portfolio Lainnya</h2>

                    @forelse($except_result as $index => $row)
                        <article class="sidebar-news-card">
                            <a href="{{ route('home.berita.show', $row->id) }}" class="sidebar-news-link">
                                <div class="sidebar-news-image">
                                    <img src="{{ asset('storage/' . $row->image) }}"
                                         alt="{{ $row->title }}">
                                </div>
                                <div class="sidebar-news-content">
                                    <h3 class="sidebar-news-title">{{ $row->title }}</h3>
                                    <p class="sidebar-news-date">
                                        {{ Carbon\Carbon::parse($row->date)->diffForHumans(null, true) }} yang lalu
                                    </p>
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