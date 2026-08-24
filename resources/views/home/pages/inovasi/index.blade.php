@extends('home.layouts.master')
@section("title","Inovasi | EMPATRA DIGITECH")
@section('css')
    <link href="{{ asset('assets/css/home/inovasi/inovasi.css') }}" rel="stylesheet">
@endsection
@section("content")

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header-content">
                    <h1 class="page-title">Inovasi Kami</h1>
                    <p class="page-subtitle">Produk &amp; solusi digital yang telah kami kembangkan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="showcase-section">
    <div class="container">

        @if($kategori_list->count())
        <div class="showcase-filter" data-aos="fade-up">
            <a href="{{ route('home.inovasi.index') }}" class="filter-pill {{ empty($kategori_active) ? 'active' : '' }}">Semua</a>
            @foreach ($kategori_list as $kategoriItem)
                <a href="{{ route('home.inovasi.index', ['kategori' => $kategoriItem]) }}"
                    class="filter-pill {{ $kategori_active == $kategoriItem ? 'active' : '' }}">{{ $kategoriItem }}</a>
            @endforeach
        </div>
        @endif

        <div class="showcase-grid">
            @forelse ($table as $index => $row)
                <div class="showcase-grid-item" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <article class="showcase-card">
                        <a href="{{ route('home.inovasi.show', $row->id) }}" class="showcase-card-image">
                            <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}" class="img-fluid">
                            @if($row->kategori)
                                <span class="showcase-badge">{{ $row->kategori }}</span>
                            @endif
                        </a>

                        <div class="showcase-card-body">
                            <a href="{{ route('home.inovasi.show', $row->id) }}" class="showcase-title-link">
                                <h3 class="showcase-title">{{ $row->title }}</h3>
                            </a>

                            <p class="showcase-excerpt">
                                {!! Str::limit(strip_tags($row->renderTrix('content')), 110) !!}
                            </p>

                            @if($row->harga !== null)
                                <div class="showcase-price">Rp {{ number_format($row->harga, 0, ',', '.') }}</div>
                            @endif

                            <a href="{{ route('home.inovasi.show', $row->id) }}" class="btn-showcase">
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="no-data-message">
                    <i class="bi bi-inbox"></i>
                    <h3>Belum Ada Inovasi</h3>
                    <p>Saat ini belum ada produk inovasi yang tersedia.</p>
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
@section("script")
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
@endsection
