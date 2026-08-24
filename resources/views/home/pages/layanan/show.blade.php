@extends('home.layouts.master')
@section("title","". $result->title ." | Empatra Digitech")
@section('css')
<link href="{{ URL::to('/') }}/assets/css/home/layanan-cta.css" rel="stylesheet">
@endsection
@section('content')
<br>
<br>
<div class="container" style="padding-top :60px">
    <div class="row">
        <div class="col-12">

            <h1 class=""><b>{{ $result->title }}</b></h1>

            <style>
                img {
                    max-width: 100%;
                    height: auto;
                }
                .attachment__caption {
                    display: none !important;
                }
            </style>
            <p >{!! $result->renderTrix("content") !!}</p>

        </div>

    </div>
</div>

@php
    $waLayananMessage = "Halo Empatra DigiTech, saya tertarik dengan layanan \"{$result->title}\", boleh minta info lebih lanjut?";
    $waLayananLink = "https://wa.me/6285151811055?text=" . urlencode($waLayananMessage);
@endphp

<section class="layanan-cta-section">
    <div class="container">
        <div class="layanan-cta-box">
            <h2 class="layanan-cta-title">Tertarik dengan layanan {{ $result->title }}?</h2>
            <p class="layanan-cta-subtitle">Konsultasikan kebutuhan Anda langsung, gratis dan tanpa kewajiban.</p>
            <div class="layanan-cta-buttons">
                <a href="{{ $waLayananLink }}" target="_blank" rel="noopener" class="layanan-btn-primary">
                    <i class='bx bxl-whatsapp'></i>
                    Tanya {{ $result->title }} via WhatsApp
                </a>
                <a href="{{ route('home.kontak.index') }}" class="layanan-btn-secondary">
                    Minta Penawaran Tertulis
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
