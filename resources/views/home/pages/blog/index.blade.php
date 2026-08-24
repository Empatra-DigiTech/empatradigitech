@extends('home.layouts.master')
@section("title","Blog & Insight | EMPATRA DIGITECH")
@section("meta_description","Artikel seputar estimasi biaya pembuatan website & aplikasi, perbandingan sistem digital, dan kebutuhan teknologi untuk UMKM — dari tim Empatra DigiTech.")
@section('css')
    <link href="{{ URL::to('/') }}/assets/css/home/blog/blog.css" rel="stylesheet">
@endsection

@section('content')

<!-- Page Header -->
<section class="blog-page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="blog-page-header-content">
                    <h1 class="blog-page-title">Blog &amp; Insight</h1>
                    <p class="blog-page-subtitle">Panduan praktis seputar biaya, teknologi, dan strategi digital untuk bisnis Anda</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog-section">
    <div class="container">

        @if($kategori_list->count())
        <div class="blog-filter" data-aos="fade-up">
            <a href="{{ route('home.blog.index') }}" class="filter-pill {{ empty($kategori_active) ? 'active' : '' }}">Semua</a>
            @foreach ($kategori_list as $kategoriItem)
                <a href="{{ route('home.blog.index', ['kategori' => $kategoriItem]) }}"
                    class="filter-pill {{ $kategori_active == $kategoriItem ? 'active' : '' }}">{{ $kategoriItem }}</a>
            @endforeach
        </div>
        @endif

        <div class="row blog-grid">
            @forelse ($table as $index => $row)
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
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
            @empty
                <div class="col-12">
                    <div class="no-data-message">
                        <i class="bi bi-journal-text"></i>
                        <h3>Belum Ada Artikel</h3>
                        <p>Saat ini belum ada artikel blog yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>

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
