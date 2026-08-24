@extends('home.layouts.master')
@section("title","Visi & Misi | EMPATRA DIGITECH")

@section('css')
    <link href="{{ asset('assets/css/home/vm/vm.css') }}" rel="stylesheet">
@endsection

@section("content")

{{-- Hero Section --}}
<section id="so" class="so section">
    <div class="so-bg">
        @if($result && $result->image)
            <img src="{{ asset('storage/' . $result->image) }}" alt="Visi Misi Banner">
        @else
            <img src="{{URL::to('/')}}/assets/img/visimisi.png" alt="Visi Misi Banner">
        @endif
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
        @if($result && ($result->visi || $result->misi))
            <div class="vm-container">

                {{-- Visi Card --}}
                @if($result->visi)
                    <div class="vm-card visi-card" data-aos="fade-right">
                        <div class="vm-card-icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                        <h3 class="vm-title">Visi</h3>
                        <div class="vm-content">
                            <p>{{ $result->visi }}</p>
                        </div>
                    </div>
                @endif

                {{-- Misi Card --}}
                @if($result->misi)
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
                                @foreach($result->misi_array as $index => $misi_item)
                                    <li>
                                        <span class="bullet">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <p>{{ trim($misi_item) }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

            </div>
        @else
            {{-- Empty State --}}
            <div class="empty-state" style="text-align: center; padding: 60px 20px; color: #6b7280;">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 20px; opacity: 0.5;">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <p style="font-size: 1.125rem; margin: 0;">Visi & Misi belum tersedia</p>
            </div>
        @endif
    </div>
</section>

@endsection
