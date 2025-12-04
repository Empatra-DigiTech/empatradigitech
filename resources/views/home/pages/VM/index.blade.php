@extends('home.layouts.master')
@section("title","Visi & Misi | EMPATRA DIGITECH")

@section("content")

{{-- Hero Section --}}
<section id="so" class="so section">
    <div class="so-bg">
        <img src="{{URL::to('/')}}/assets/img/visimisi.png" alt="Visi Misi Banner">
    </div>
</section>

{{-- Visi Misi Content --}}
<section id="visi-misi-content" class="mt-5">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up" style="padding-top: 60px;">
        <h2>Visi & Misi</h2>
    </div>
    <!-- End Section Title -->

    <!-- Visi Misi Cards -->
    <div class="container">
        <div class="vm-container">

            {{-- Visi Card --}}
            <div class="vm-card visi-card" data-aos="fade-right">
                <div class="vm-card-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <h3 class="vm-title">Visi</h3>
                <div class="vm-content">
                    <p>Menjadi mitra terpercaya dalam transformasi digital yang memberdayakan, inovatif, dan berdampak — dengan semangat kolaborasi dan keberanian berkarya.</p>
                </div>
            </div>

            {{-- Misi Card --}}
            <div class="vm-card misi-card" data-aos="fade-left">
                <div class="vm-card-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                </div>
                <h3 class="vm-title">Misi</h3>
                <div class="vm-content">
                    <ul class="misi-list">
                        <li>
                            <span class="bullet">01</span>
                            <p>Menyediakan solusi digital kreatif dan relevan yang mendukung pertumbuhan klien di berbagai sektor.</p>
                        </li>
                        <li>
                            <span class="bullet">02</span>
                            <p>Mengembangkan produk teknologi yang inovatif dan berorientasi pada kebutuhan masa depan.</p>
                        </li>
                        <li>
                            <span class="bullet">03</span>
                            <p>Membangun budaya kerja yang kolaboratif, adaptif, dan energik, berlandaskan empat nilai utama: keberanian, inovasi, karya, dan energi.</p>
                        </li>
                        <li>
                            <span class="bullet">04</span>
                            <p>Mendorong transformasi digital yang inklusif dan berkelanjutan, dengan menjunjung tinggi nilai-nilai lokal dalam perspektif global.</p>
                        </li>
                        <li>
                            <span class="bullet">05</span>
                            <p>Menjadi ruang tumbuh bagi talenta muda untuk bereksperimen, berinovasi, dan berkontribusi melalui teknologi.</p>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
