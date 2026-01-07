@extends('home.layouts.master')
@section("title", $result->title . " | EMPATRA DIGITECH")

@section('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('assets/css/berita/show.css') }}" rel="stylesheet">
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
                                <span class="meta-item">
                                    <i class="bi bi-person"></i>
                                    {{ $result->creator }}
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

                        <!-- Share Section -->
                        <div class="share-section">
                            <p class="share-title">Bagikan Artikel Ini:</p>
                            <div class="share-buttons">
                                <button type="button" class="btn-share facebook" id="share-facebook" aria-label="Share on Facebook">
                                    <i class='bx bxl-facebook'></i>
                                </button>
                                <button type="button" class="btn-share whatsapp" id="share-whatsapp" aria-label="Share on WhatsApp">
                                    <i class='bx bxl-whatsapp'></i>
                                </button>
                                <button type="button" class="btn-share twitter" id="share-twitter" aria-label="Share on Twitter">
                                    <i class='bx bxl-twitter'></i>
                                </button>
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

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ========================================
        // Facebook Share
        // ========================================
        document.getElementById('share-facebook').addEventListener('click', function() {
            const url = encodeURIComponent(window.location.href);
            const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            window.open(facebookShareUrl, '_blank', 'width=600,height=400');
        });


        // ========================================
        // WhatsApp Share
        // ========================================
        document.getElementById('share-whatsapp').addEventListener('click', function() {
            const text = encodeURIComponent(document.title + " " + window.location.href);
            const whatsappShareUrl = `https://api.whatsapp.com/send?text=${text}`;
            window.open(whatsappShareUrl, '_blank');
        });


        // ========================================
        // Twitter Share
        // ========================================
        document.getElementById('share-twitter').addEventListener('click', function() {
            const text = encodeURIComponent(document.title);
            const url = encodeURIComponent(window.location.href);
            const twitterShareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
            window.open(twitterShareUrl, '_blank', 'width=600,height=400');
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
                    link.style.color = '#667eea';
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
