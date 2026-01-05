@extends('home.layouts.master')
@section("title","Berita | EMPATRA DIGITECH")

@section('css')
    <link href="assets/css/berita/card.css" rel="stylesheet">
@endsection

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header-content">
                    <h1 class="page-title">Berita Terkini</h1>
                    <p class="page-subtitle">Informasi dan berita terbaru dari kami</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Grid Section -->
<section class="news-section">
    <div class="container">
        <div class="row news-grid">
            
            @forelse ($table as $index => $row)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="news-card">
                        <div class="news-card-image">
                            <a href="{{ route('home.berita.show', $row->id) }}">
                                <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}" class="img-fluid">
                                <div class="image-overlay">
                                    <span class="read-more-badge">Baca Selengkapnya</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="news-card-body">
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ Carbon\Carbon::parse($row->date)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            
                            <a href="{{ route('home.berita.show', $row->id) }}" class="news-title-link">
                                <h3 class="news-title">{{ $row->title }}</h3>
                            </a>
                            
                            <p class="news-excerpt">
                                {!! Str::limit(strip_tags($row->renderTrix('content')), 120) !!}
                            </p>
                            
                            <a href="{{ route('home.berita.show', $row->id) }}" class="btn-read-more">
                                Selengkapnya
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-data-message">
                        <i class="bi bi-inbox"></i>
                        <h3>Belum Ada Berita</h3>
                        <p>Saat ini belum ada berita yang tersedia.</p>
                    </div>
                </div>
            @endforelse
            
        </div>
        
        <!-- Pagination -->
        @if($table->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="pagination-wrapper">
                        {!! $table->links() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ========================================
        // News Card Hover Animation
        // ========================================
        const newsCards = document.querySelectorAll('.news-card');
        
        newsCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        
        // ========================================
        // Smooth Scroll Animation on Load
        // ========================================
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        newsCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });
        
        
        // ========================================
        // Image Lazy Loading Enhancement
        // ========================================
        const images = document.querySelectorAll('.news-card-image img');
        
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        });
        
    });
</script>
@endsection