@extends('home.layouts.master')
@section("title","Team | EMPATRA DIGITECH")

@section('css')
    <link href="assets/css/home/team/team.css" rel="stylesheet">
@endsection

@section("content")

{{-- Hero Section --}}
<section id="so" class="so section">
    <div class="so-bg">
        <img src="{{URL::to('/')}}/assets/img/team.png" alt="Team Banner">
    </div>
</section>

{{-- Team Section --}}
<section>
    <!-- Section Title -->
    <div class="container section-title mt-5" data-aos="fade-up" style="padding-top: 60px;">
        <h2>TEAM</h2>
    </div>
    <!-- End Section Title -->

    <div class="container">
        @if($table->isEmpty())
            {{-- Empty State --}}
            <div class="empty-state">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <p>Belum ada data tim tersedia</p>
            </div>
        @else
            {{-- Team Cards --}}
            <ul class="cards">
                @foreach ($table as $index => $row)
                <li>
                    <div class="card">
                        <img src="{{ asset('storage/' . $row->image) }}" class="card__image" alt="{{ $row->nama }}" />
                        <div class="card__overlay">
                            <div class="card__header">
                                <div class="card__header-text">
                                    <h3 class="card__title">{{ $row->nama }}</h3>
                                    <span class="card__status">{{ $row->jabatan }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>

@endsection
