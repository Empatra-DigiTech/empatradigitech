@extends('home.layouts.master')
@section("title", $result->title." | EMPATRA DIGITECH")
@section('css')
    <link href="{{ asset('assets/css/home/inovasi/inovasi.css') }}" rel="stylesheet">
@endsection
@section('content')

<section class="detail-hero">
    <div class="container">
        <nav class="detail-breadcrumb" data-aos="fade-up">
            <a href="{{ route('home.inovasi.index') }}"><i class='bx bx-arrow-back'></i> Kembali ke Inovasi</a>
        </nav>
    </div>
</section>

<section class="detail-section">
    <div class="container">
        <div class="row detail-row g-0">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="detail-image-wrap">
                    <img src="{{ asset('storage/' . $result->image) }}" alt="{{ $result->title }}" class="detail-image">
                    @if($result->kategori)
                        <span class="showcase-badge showcase-badge-onimage">{{ $result->kategori }}</span>
                    @endif
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="detail-info">
                    @if($result->kategori)
                        <span class="detail-kategori-tag">{{ $result->kategori }}</span>
                    @endif
                    <h1 class="detail-title">{{ $result->title }}</h1>
                    <div class="detail-meta">
                        <i class='bx bx-calendar'></i>
                        {{ Carbon\Carbon::parse($result->date)->translatedFormat('l, d F Y') }}
                    </div>

                    <div class="detail-divider"></div>

                    <div class="detail-content">
                        {!! $result->renderTrix("content") !!}
                    </div>

                    <a href="{{ route('home.kontak.index') }}" class="btn-showcase detail-cta">
                        Tanya Tentang Produk Ini
                        <i class='bx bx-message-rounded-dots'></i>
                    </a>
                </div>
            </div>
        </div>

        @if($except_result->count())
        <div class="detail-related" data-aos="fade-up">
            <h2 class="detail-related-title">Inovasi Lainnya</h2>
            <div class="row showcase-grid gx-3 gy-3">
                @foreach ($except_result as $row)
                    <div class="col-lg-4 col-md-6 col-6">
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
                                    {!! Str::limit(strip_tags($row->renderTrix('content')), 90) !!}
                                </p>
                                <a href="{{ route('home.inovasi.show', $row->id) }}" class="btn-showcase">
                                    Lihat Detail
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
