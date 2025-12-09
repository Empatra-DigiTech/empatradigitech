@extends('home.layouts.master')
@section("title", $result->title . " | EMPATRA DIGITECH")

@section('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home.berita.index') }}">Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($result->title, 50) }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Article Content Section -->
<section class="article-section">
    <div class="container">
        <div class="row">
            
            <!-- Main Article Column -->
            <div class="col-lg-9">
                <article class="article-detail">
                    
                    <!-- Article Header -->
                    <div class="article-header">
                        <h1 class="article-title">{{ $result->title }}</h1>
                        
                        <div class="article-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ Carbon\Carbon::parse($result->date)->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ $result->creator }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="article-featured-image">
                        <img src="{{ asset('storage/' . $result->image) }}" alt="{{ $result->title }}" class="img-fluid">
                    </div>
                    
                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $result->renderTrix("content") !!}
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="article-share">
                        <h4 class="share-title">Bagikan Artikel Ini</h4>
                        <div class="share-buttons">
                            <button type="button" class="btn-share btn-share-facebook" id="share-facebook">
                                <i class='bx bxl-facebook'></i>
                                <span>Facebook</span>
                            </button>
                            <button type="button" class="btn-share btn-share-whatsapp" id="share-whatsapp">
                                <i class='bx bxl-whatsapp'></i>
                                <span>WhatsApp</span>
                            </button>
                            <button type="button" class="btn-share btn-share-twitter" id="share-twitter">
                                <i class='bx bxl-twitter'></i>
                                <span>Twitter</span>
                            </button>
                            <button type="button" class="btn-share btn-share-link" id="copy-link">
                                <i class='bx bx-link'></i>
                                <span>Salin Link</span>
                            </button>
                        </div>
                    </div>
                    
                </article>
            </div>
            
            <!-- Sidebar Column -->
            <div class="col-lg-3">
                <aside class="article-sidebar">
                    
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Berita Lainnya</h3>
                        
                        <div class="related-news-list">
                            @forelse($except_result as $index => $row)
                                <article class="related-news-item">
                                    <a href="{{ route('home.berita.show', $row->id) }}" class="related-news-link">
                                        <div class="related-news-image">
                                            <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
                                        </div>
                                        <div class="related-news-content">
                                            <h4 class="related-news-title">{{ Str::limit($row->title, 60) }}</h4>
                                            <span class="related-news-date">
                                                <i class="bi bi-clock"></i>
                                                {{ Carbon\Carbon::parse($row->date)->diffForHumans(null, true) }} yang lalu
                                            </span>
                                        </div>
                                    </a>
                                </article>
                            @empty
                                <p class="no-related-news">Tidak ada berita lainnya saat ini.</p>
                            @endforelse
                        </div>
                        
                    </div>
                    
                </aside>
            </div>
            
        </div>
    </div>
</section>

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
        // Copy Link to Clipboard
        // ========================================
        document.getElementById('copy-link').addEventListener('click', function() {
            const currentUrl = window.location.href;
            const button = this;
            
            // Create temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = currentUrl;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Change button text temporarily
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bx bx-check"></i><span>Link Disalin!</span>';
            button.style.backgroundColor = '#28a745';
            
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.style.backgroundColor = '';
            }, 2000);
        });
        
        
        // ========================================
        // Related News Hover Effect
        // ========================================
        const relatedNewsItems = document.querySelectorAll('.related-news-item');
        
        relatedNewsItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        
        // ========================================
        // Smooth Scroll for Anchor Links in Content
        // ========================================
        const contentLinks = document.querySelectorAll('.article-content a[href^="#"]');
        
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
        
    });
</script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
@endsection