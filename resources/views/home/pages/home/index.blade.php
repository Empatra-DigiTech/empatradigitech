@extends('home.layouts.master')
@section("title","Home | EMPATRA DIGITECH")
@section('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
    </style>
    <!-- font css-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/home/swiper/style.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/home/kalender.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/home/navbar.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/home/tautan.css">
@endsection


@section('content')
    <!-- Swiper-->

    <section class="section swiper-container swiper-slider swiper-slider-2 swiper-slider-3" data-loop="true"
        data-autoplay="5000" data-simulate-touch="false" data-slide-effect="fade" style="text-align: left">
        <div class="swiper-wrapper text-sm-left">
            @forelse ($table_banner as $index => $row)
                <div class="swiper-slide context-dark" id="swipper" data-slide-bg="{{ asset('storage/' . $row->image) }}"
                    alt="">
                    <div class="swiper-slide-caption section-md">
                        <div class="container" id="swipper_tittle">
                            <div class="row">
                                <div class="col-sm-9 col-md-8 col-lg-7 col-xxl-7 offset-lg-1 offset-xxl-0">
                                    <h1 class="oh swiper-title kanit-black">
                                        <span class="d-inline-block" data-caption-animate="slideInUp"
                                            data-caption-delay="0">{{ $row->title }}</span>
                                    </h1>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            <div class="swiper-slide context-dark" id="swipper" data-slide-bg="https://i.pinimg.com/474x/80/c0/ec/80c0ec0db9f3db5bb0b4ebcf128797da.jpg"
                alt="">
                <div class="swiper-slide-caption section-md">
                    <div class="container" id="swipper_tittle">
                        <div class="row">
                            <div class="col-sm-9 col-md-8 col-lg-7 col-xxl-7 offset-lg-1 offset-xxl-0">
                                <h1 class="oh swiper-title kanit-black">
                                    <span class="d-inline-block" data-caption-animate="slideInUp"
                                        data-caption-delay="0">DISHUB</span>
                                </h1>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Swiper Pagination-->
        <div class="swiper-pagination" data-bullet-custom="true"></div>
        <!-- Swiper Navigation-->
        <div class="swiper-button-prev">
            <div class="preview">
                <div class="preview__img"></div>
            </div>
            <div class="swiper-button-arrow">
                <i class='bx bx-left-arrow-alt'></i>
            </div>
        </div>
        <div class="swiper-button-next">
            <div class="swiper-button-arrow">
                <i class='bx bx-right-arrow-alt'></i>
            </div>
            <div class="preview">
                <div class="preview__img"></div>
            </div>
        </div>
    </section>

    </section><!-- /Hero Section -->

    <!-- Layanan Section -->
<section id="layanan" class="featured-services section light-background">
    <div class="container section-title" data-aos="fade-up">
        <h2 class="p-5">Layanan</h2>
    </div>

    <!-- Layanan Umum -->
    <div class="card-container container">
        @forelse ($table_layanan as $index => $row)
        <div class="card-layanan">
            <a href="{{ route('home.layanan.show', $row->id) }}">
                <img src="{{ asset('storage/' . $row->image) }}" alt="{{ $row->title }}">
            </a>
            <p><a href="{{ route('home.layanan.show', $row->id) }}" class="">{{ $row->title }}</a></p>
        </div>
        @empty
        <p>Tidak ada layanan tersedia</p>
        @endforelse
    </div>

    <!-- Paket Website & App Section -->
    <div class="container-fluid paket-section mt-5 pt-5 pb-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Paket Layanan Digital</h2>
                <p class="section-subtitle">Pilih paket yang sesuai dengan kebutuhan bisnis Anda</p>
                <div class="btn-group mt-4" role="group" aria-label="Pilih Paket">
                    <button type="button" class="btn btn-paket active" id="btn-website" onclick="showPaket('website')">
                        <i class='bx bx-laptop'></i> Website Packages
                    </button>
                    <button type="button" class="btn btn-paket" id="btn-app" onclick="showPaket('app')">
                        <i class='bx bx-mobile-alt'></i> Mobile App Packages
                    </button>
                </div>
            </div>

            <!-- Paket Website -->
            <div id="paket-website" class="paket-container active">
                <div class="row g-4 justify-content-center align-items-stretch">
                    @forelse ($paket_website ?? [] as $index => $paket)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
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
                                <h4 class="features-title">Features</h4>
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
                                <a href="{{ route('home.kontak.index') }}?paket=website_{{ $paket->id }}"
                                   class="btn-purchase">
                                    Purchase Now
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class='bx bx-package'></i>
                            <p>Website packages coming soon</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Paket Aplikasi -->
            <div id="paket-app" class="paket-container">
                <div class="row g-4 justify-content-center align-items-stretch">
                    @forelse ($paket_app ?? [] as $index => $paket)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
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
                                <h4 class="features-title">Features</h4>
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
                                <a href="{{ route('home.kontak.index') }}?paket=app_{{ $paket->id }}"
                                   class="btn-purchase">
                                    Purchase Now
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class='bx bx-mobile-alt'></i>
                            <p>Mobile app packages coming soon</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Paket Section Background */
.paket-section {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    position: relative;
    overflow: hidden;
}

.paket-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.paket-section .container {
    position: relative;
    z-index: 1;
}

/* Section Title */
.section-title {
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.section-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
}

/* Toggle Buttons */
.btn-paket {
    padding: 14px 35px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border-radius: 0;
    backdrop-filter: blur(10px);
}

.btn-paket:first-child {
    border-radius: 50px 0 0 50px;
}

.btn-paket:last-child {
    border-radius: 0 50px 50px 0;
}

.btn-paket:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.5);
}

.btn-paket.active {
    background: white;
    color: #1e3c72;
    border-color: white;
    box-shadow: 0 4px 20px rgba(255, 255, 255, 0.3);
}

.btn-paket i {
    margin-right: 8px;
    font-size: 1.2em;
}

/* Container Paket */
.paket-container {
    display: none;
    animation: fadeInUp 0.6s ease;
}

.paket-container.active {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pricing Card */
.pricing-card {
    background: #2d3e50;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.pricing-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.pricing-card.recommended {
    background: #344556;
    border: 3px solid #ff9800;
    transform: scale(1.05);
}

.pricing-card.recommended:hover {
    transform: scale(1.08) translateY(-15px);
}

/* Recommended Badge */
.recommended-badge {
    position: absolute;
    top: 20px;
    right: -35px;
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: white;
    padding: 8px 45px;
    transform: rotate(45deg);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
    z-index: 10;
}

/* Pricing Header */
.pricing-header {
    padding: 40px 30px 30px;
    text-align: center;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
}

.package-name {
    color: white;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.price-wrapper {
    margin: 20px 0;
}

.currency {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.2rem;
    font-weight: 500;
    vertical-align: top;
}

.price {
    color: white;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
}

.package-tagline {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.95rem;
    margin-top: 10px;
}

/* Pricing Body */
.pricing-body {
    padding: 30px;
    flex-grow: 1;
}

.features-title {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features-list li {
    color: rgba(255, 255, 255, 0.85);
    padding: 12px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: flex-start;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.features-list li:last-child {
    border-bottom: none;
}

.features-list li:hover {
    color: white;
    padding-left: 10px;
}

.features-list li i {
    color: #4caf50;
    font-size: 1.3em;
    margin-right: 12px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Pricing Footer */
.pricing-footer {
    padding: 30px;
    background: rgba(0, 0, 0, 0.2);
}

.btn-purchase {
    display: block;
    width: 100%;
    padding: 15px 30px;
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
}

.btn-purchase:hover {
    background: linear-gradient(135deg, #ff5722, #ff9800);
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(255, 152, 0, 0.5);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: rgba(255, 255, 255, 0.7);
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 1.2rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 992px) {
    .pricing-card.recommended {
        transform: scale(1);
    }

    .pricing-card.recommended:hover {
        transform: scale(1.02) translateY(-10px);
    }
}

@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }

    .btn-paket {
        padding: 12px 25px;
        font-size: 0.9rem;
    }

    .price {
        font-size: 2rem;
    }

    .package-name {
        font-size: 1.5rem;
    }

    .pricing-header,
    .pricing-body,
    .pricing-footer {
        padding: 25px 20px;
    }
}

@media (max-width: 576px) {
    .btn-paket i {
        display: none;
    }
}
</style>

<script>
function showPaket(type) {
    // Hide all containers with fade out
    document.querySelectorAll('.paket-container').forEach(container => {
        container.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            container.classList.remove('active');
        }, 300);
    });

    // Remove active class from all buttons
    document.querySelectorAll('.btn-paket').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected container with fade in
    setTimeout(() => {
        if (type === 'website') {
            document.getElementById('paket-website').classList.add('active');
            document.getElementById('btn-website').classList.add('active');
        } else {
            document.getElementById('paket-app').classList.add('active');
            document.getElementById('btn-app').classList.add('active');
        }
    }, 350);
}
</script>




        <!-- Features Details Section -->
        <section id="features-details" class="features-details section text-center">
            <div class="container section-title" data-aos="fade-up">
                <h2>Berita Terpopuler</h2>
            </div>
            <div class="container">
                <!-- <h3 class="d-flex justify-content-start">Berita Utama</h3> -->
                @if($table_view != null)
                <div class="row gy-4 justify-content-between features-item">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('storage/' . $table_view->image) }}" class="img-fluid" alt="">
                    </div>

                    <div class="col-lg-5 d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="content">
                            <p>
                                {{ Carbon\Carbon::parse($table_view->date)->translatedFormat('l,d F Y') }}
                            </p>
                            <small><i class='bx bx-show'></i> Dilihat {{ $count_view->total }} kali</small>
                            <h4>{{ $table_view->title }}</h4>
                            <p>
                                {!! Str::limit(strip_tags($table_view->renderTrix('content')), 120) !!}
                            </p>
                            <a href="{{ route('home.berita.show', $table_view->id) }}" class="btn more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                @else
                    <p>No Data</p>
                @endif
                {{-- berita --}}
                <div class="berita py-5">
                    <div class="py-5">
                        <div class="row g-4 justify-content-center">
                            @forelse ($table_berita->sortByDesc('date')->slice(0, 3) as $index => $row)
                                <div class="col-md-6 col-lg-4 col-xl-4 wow fadeInUp" data-aos="zoom-in" data-wow-delay="0.3s">
                                    <div class="berita-item rounded">
                                        <div class="berita-img rounded-top">
                                            <a href="{{ route('home.berita.show', $row->id) }}"><img
                                                    src="{{ asset('storage/' . $row->image) }}"
                                                    class="img-fluid rounded-top w-100" alt=""></a>
                                        </div>
                                        <div class="berita-content rounded-bottom bg-light p-4">
                                            <div class="berita-content-inner">
                                                <p class="card-text">
                                                    {{ Carbon\Carbon::parse($row->date)->translatedFormat('l,d F Y') }}</p>
                                                <a href="{{ route('home.berita.show', $row->id) }}"><h5 class="mb-4">{{ $row->title }}</h5></a>
                                                <p class="mb-3">{!! Str::limit(strip_tags($row->renderTrix('content')), 100) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>tidak ada data</p>
                            @endforelse
                        </div>
                        <div>
                            <a href="{{ route('home.berita.index') }}" class="btn mt-5 mb-2">Semua Berita</a>
                        </div>
                    </div>
                </div>
        </section><!-- /Features Details Section -->

        <!-- Calender -->
        <section name="calendar">
            @include('home.component.kalender')
        </section>

        <!--end Calendar-->


        <div class="container section-title" data-aos="fade-up">
            <h2 class="p-5">Link</h2>
        </div>
        <div class="container">
          <div class="row mt-2 justify-content-center">
              @forelse ($table_tautan->chunk(5) as $chunk)
                  <div class="col-md-4 d-flex justify-content-center mt-5">
                      <div class="card list" data-aos="fade-up" style="width: 18rem; -webkit-box-shadow: none !important; -moz-box-shadow: none !important; box-shadow: none !important">
                          <ul>
                            @foreach ($chunk as $row)
                                <li><a href="{{ URL::to($row->url) }}" class="card-link" target="_blank">{{ $row->title }}</a></li>
                            @endforeach
                          </ul>
                      </div>
                  </div>
              @empty
                  <p>tidak ada data</p>
              @endforelse
          </div>
      </div>


        <!-- Contact Section -->
        <section id="kontak" class="contact section">

            <!-- Section Title -->
            <div class="container section-title mt-5" data-aos="fade-up">
                <h2>Kontak</h2>

            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-geo-alt"></i>
                            <h3>Address</h3>
                            <p>{{ $table_pengaturan->website_address }}</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-telephone"></i>
                            <h3>Call Us</h3>
                            <p>{{ $table_pengaturan->website_phone }}</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-envelope"></i>
                            <h3>Email Us</h3>
                            <p>{{ $table_pengaturan->website_email }}</p>
                        </div>
                    </div><!-- End Info Item -->

                </div>

                <div class="row gy-4 mt-1">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        {{-- <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d688.1506512226823!2d112.61389434101307!3d-7.956754435114845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827beffc2665%3A0x9dd38763e9a56b77!2sDinas%20Pendidikan%20dan%20Kebudayaan%20Kota%20Malang!5e0!3m2!1sen!2sid!4v1720151847698!5m2!1sen!2sid"
                            frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
                            <iframe id="maps_big" src="" frameborder="0"></iframe>
                    </div><!-- End Google Maps -->

                    <div class="col-lg-6 mb-5" data-aos="fade-up" data-aos-delay="300">
                        <div class="custom-content card ">
                            <form action="{{ route('home.kontak.store') }}" method="post" autocomplete="off"
                                onsubmit="confirm('Apakah anda yakin ingin mengirim pesan ini?')"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row p-3">
                                    <div class="form-group col-md-6">
                                        <label for="username">Nama</label>
                                        <input type="text" class="form-control border border-dark" id="username"
                                            placeholder="Masukan Nama Anda" name="name" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email2">Email</label>
                                        <input type="email" class="form-control border border-dark" id="email"
                                            placeholder="Masukan Email Anda" name="email" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="username">Gambar</label>
                                        <input type="file" class="form-control border border-dark" accept="image/*"
                                            id="image" name="image" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="username">Subject</label>
                                        <input type="text" class="form-control border border-dark" id="subject"
                                            placeholder="Masukan Judul" name="subject" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="comment">Pesan Anda</label>
                                        <textarea class="form-control border border-dark" id="comment" rows="5" name="message"></textarea>
                                    </div>
                                    <div class="card-action">
                                        <button class="btn" type="submit"
                                            style="background: #2a2f5b; color:white">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- End Contact Form -->

                </div>

            </div>
        </section><!-- /Contact Section -->
    @endsection

    @section('script')
        <!--kalender-->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="{{ URL::to('/') }}/assets/js/home/kalender.js"></script>
        <script src="{{ URL::to('/') }}/assets/js/home/swiper/core.min.js"></script>
        <script src="{{ URL::to('/') }}/assets/js/home/swiper/script.js"></script>
        <script src="{{URL::to('/')}}/assets/js/home/navbar.js"></script>

    @endsection
