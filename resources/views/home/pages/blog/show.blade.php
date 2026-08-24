@extends('home.layouts.master')
@section("title", $result->title . " | Blog EMPATRA DIGITECH")
@section("meta_description", $result->excerpt)
@section('og_tags')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $result->title }}">
    <meta property="og:description" content="{{ $result->excerpt }}">
    <meta property="og:image" content="{{ asset('storage/' . $result->image) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
@endsection
@section('css')
    <link href="{{ URL::to('/') }}/assets/css/home/blog/blog.css" rel="stylesheet">
@endsection

@section('content')

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": @json($result->title),
    "description": @json($result->excerpt),
    "image": @json(asset('storage/' . $result->image)),
    "datePublished": @json($result->created_at->toIso8601String()),
    "dateModified": @json($result->updated_at->toIso8601String()),
    "author": {
        "@type": "Organization",
        "name": "Empatra DigiTech"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Empatra DigiTech"
    }
}
</script>

<section class="blog-detail-hero">
    <div class="container">
        <nav class="blog-breadcrumb" data-aos="fade-up">
            <a href="{{ route('home.blog.index') }}"><i class='bx bx-arrow-back'></i> Kembali ke Blog</a>
        </nav>
    </div>
</section>

<section class="blog-detail-section">
    <div class="container">
        <div class="blog-detail-wrapper" data-aos="fade-up">

            @if($result->kategori)
                <span class="blog-detail-kategori">{{ $result->kategori }}</span>
            @endif

            <h1 class="blog-detail-title">{{ $result->title }}</h1>

            <div class="blog-detail-meta">
                <span><i class='bx bx-calendar'></i> {{ $result->created_at->translatedFormat('d F Y') }}</span>
                <span><i class='bx bx-time-five'></i> {{ max(1, ceil(str_word_count(strip_tags($result->renderTrix('content'))) / 200)) }} menit baca</span>
            </div>

            <div class="blog-detail-image">
                <img src="{{ asset('storage/' . $result->image) }}" alt="{{ $result->title }}">
            </div>

            <div class="blog-detail-body">
                {!! $result->renderTrix("content") !!}
            </div>

            <!-- Strategic End-of-Article CTA -->
            @php
                $waBlogMessage = "Halo Empatra DigiTech, saya baru baca artikel \"{$result->title}\" dan tertarik untuk konsultasi membuat website/aplikasi serupa.";
                $waBlogLink = "https://wa.me/6285151811055?text=" . urlencode($waBlogMessage);
            @endphp
            <div class="blog-cta-box">
                <div class="blog-cta-icon"><i class='bx bxl-whatsapp'></i></div>
                <h3 class="blog-cta-title">Butuh Website atau Aplikasi Seperti Ini?</h3>
                <p class="blog-cta-subtitle">Konsultasi gratis dengan tim Empatra DigiTech, ceritakan kebutuhan Anda dan dapatkan rekomendasi solusi yang tepat.</p>
                <a href="{{ $waBlogLink }}" target="_blank" rel="noopener" class="blog-cta-button">
                    <i class='bx bxl-whatsapp'></i>
                    Konsultasi dengan Empatra DigiTech
                </a>
            </div>

        </div>

        <!-- Related Articles -->
        @if($related_result->count())
        <div class="blog-related" data-aos="fade-up">
            <h2 class="blog-related-title">Artikel Terkait</h2>
            <div class="row">
                @foreach ($related_result as $row)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="blog-card">
                            <a href="{{ route('home.blog.show', $row->slug) }}" class="blog-card-image">
                                <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}" class="img-fluid">
                                @if($row->kategori)
                                    <span class="blog-badge">{{ $row->kategori }}</span>
                                @endif
                            </a>
                            <div class="blog-card-body">
                                <span class="blog-card-date">
                                    <i class='bx bx-calendar'></i>
                                    {{ $row->created_at->translatedFormat('d F Y') }}
                                </span>
                                <a href="{{ route('home.blog.show', $row->slug) }}" class="blog-title-link">
                                    <h2 class="blog-title">{{ $row->title }}</h2>
                                </a>
                                <p class="blog-excerpt">{{ $row->excerpt }}</p>
                                <a href="{{ route('home.blog.show', $row->slug) }}" class="btn-blog-read">
                                    Baca Selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
@section('script')
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
@endsection
