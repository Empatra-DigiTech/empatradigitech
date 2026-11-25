
@extends('home.layouts.master')

@section("title", "Struktur Organisasi | EMPATRA DIGITECH")

@section('meta_description', 'Struktur organisasi Empatra Digitech.')

@section('css')
    <link href="{{ asset('assets/css/home/team/team.css') }}" rel="stylesheet">
@endsection

@section("content")

{{-- Hero Section --}}
<section id="so" class="so section">
    <div class="so-bg">
        <img src="{{ URL::to('/') }}/assets/img/SA.png" alt="Struktur Organisasi Banner">
    </div>
</section>

{{-- Struktur Organisasi Section --}}
<section>

    {{-- Section Title --}}
    <div class="container section-title mt-5" data-aos="fade-up" style="padding-top: 60px;">
        <h2>STRUKTUR ORGANISASI</h2>
    </div>
    {{-- End Section Title --}}

    <div class="container">
        @if(isset($result) && $result->image)
            <img
                src="{{ asset('media/' . $result->image) }}"
                class="img-fluid w-100"
                alt="Struktur Organisasi Empatra Digitech"
                loading="lazy"
                decoding="async"
            >
        @else
            <div class="empty-state">
                <p>Belum ada gambar struktur organisasi tersedia.</p>
            </div>
        @endif
    </div>

</section>

@endsection
