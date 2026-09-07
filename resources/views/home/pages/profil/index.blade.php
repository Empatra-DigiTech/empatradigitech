@extends('home.layouts.master')
@section("title","Profil Perusahaan | EMPATRA DIGITECH")
@section('meta_description', 'Profil Empatra Digitech: perusahaan solusi digital yang menghadirkan jasa pembuatan website, aplikasi, dan sistem custom untuk bisnis Anda.')

@section('content')

<section class="profil-section">

    <div class="profil-container">

        {{-- =========================================
             HEADER
        ========================================== --}}
        <div class="profil-header">

            <span class="profil-label">
                PROFIL PERUSAHAAN
            </span>

            <h1 class="profil-title">
                {{ $table_pengaturan->website_name ?? 'Empatra Digitech' }}
            </h1>

            @if(!empty($table_pengaturan->website_motto))
            <p class="profil-motto">
                {{ $table_pengaturan->website_motto }}
            </p>
            @endif

        </div>


        {{-- =========================================
             DESCRIPTION
        ========================================== --}}
        <div class="profil-about">

            <p>
                {{ $table_pengaturan->website_name ?? 'Empatra Digitech' }} adalah perusahaan
                solusi digital yang membantu bisnis membangun kehadiran online melalui
                website, aplikasi, dan sistem digital yang profesional, modern, dan
                terpercaya. Kami percaya setiap bisnis punya kebutuhan yang unik, sehingga
                setiap project kami kerjakan dengan pendekatan yang disesuaikan dari awal
                konsultasi hingga purna jual.
            </p>

        </div>


        {{-- =========================================
             STATS
        ========================================== --}}
        @php
            $profilStats = [
                ['value' => $table_pengaturan->stat_projects ?? null, 'label' => 'Project Selesai'],
                ['value' => $table_pengaturan->stat_clients ?? null, 'label' => 'Klien Puas'],
                ['value' => $table_pengaturan->stat_years_experience ?? null, 'label' => 'Tahun Pengalaman'],
                ['value' => $table_pengaturan->stat_industries ?? null, 'label' => 'Industri Terlayani'],
            ];
            $profilStats = array_filter($profilStats, fn($stat) => !empty($stat['value']));
        @endphp

        @if(count($profilStats))
        <div class="profil-stats">
            @foreach($profilStats as $stat)
                <div class="profil-stat-card">
                    <strong>{{ $stat['value'] }}</strong>
                    <span>{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
        @endif


        {{-- =========================================
             QUICK LINKS
        ========================================== --}}
        <div class="profil-links">

            <a href="{{ route('home.VM.index') }}" class="profil-link-card">
                <span class="profil-link-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>
                <span class="profil-link-text">
                    <strong>Visi & Misi</strong>
                    <small>Arah dan tujuan perusahaan kami</small>
                </span>
                <svg class="profil-link-arrow" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h13"/>
                    <path d="m13 6 6 6-6 6"/>
                </svg>
            </a>

            <a href="{{ route('home.SO.index') }}" class="profil-link-card">
                <span class="profil-link-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="8.5" y="14" width="7" height="7" rx="1.5"/>
                        <path d="M6.5 10v2a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2"/>
                        <path d="M12 14v-2"/>
                    </svg>
                </span>
                <span class="profil-link-text">
                    <strong>Struktur Organisasi</strong>
                    <small>Susunan tim manajemen kami</small>
                </span>
                <svg class="profil-link-arrow" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h13"/>
                    <path d="m13 6 6 6-6 6"/>
                </svg>
            </a>

            <a href="{{ route('home.team.index') }}" class="profil-link-card">
                <span class="profil-link-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="9" cy="8" r="3.2"/>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/>
                        <circle cx="17.5" cy="9" r="2.5"/>
                        <path d="M15.5 14.3c2.4.5 4.5 2.5 4.5 5.7"/>
                    </svg>
                </span>
                <span class="profil-link-text">
                    <strong>Tim Kami</strong>
                    <small>Orang-orang di balik project Anda</small>
                </span>
                <svg class="profil-link-arrow" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h13"/>
                    <path d="m13 6 6 6-6 6"/>
                </svg>
            </a>

            <a href="{{ route('home.portofolio.index') }}" class="profil-link-card">
                <span class="profil-link-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M4 7h16v13H4z"/>
                        <path d="M8 7V4h8v3"/>
                        <path d="M8 12h8"/>
                        <path d="M8 16h5"/>
                    </svg>
                </span>
                <span class="profil-link-text">
                    <strong>Portofolio</strong>
                    <small>Project yang sudah kami selesaikan</small>
                </span>
                <svg class="profil-link-arrow" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h13"/>
                    <path d="m13 6 6 6-6 6"/>
                </svg>
            </a>

        </div>


        {{-- =========================================
             CTA
        ========================================== --}}
        <div class="profil-cta">

            <div class="profil-cta-text">
                <span>Punya project atau ide?</span>
                <h3>Mari konsultasikan kebutuhan Anda.</h3>
            </div>

            <a href="{{ route('home.kontak.index') }}" class="profil-cta-button">
                Hubungi Kami
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h13"/>
                    <path d="m13 6 6 6-6 6"/>
                </svg>
            </a>

        </div>

    </div>

</section>


<style>
    /* =========================================
       PROFIL SECTION
    ========================================= */

    .profil-section {
        position: relative;

        width: 100%;

        padding: 130px 20px 70px;

        background: #ffffff;
    }

    .profil-container {
        width: min(1000px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .profil-header {
        text-align: center;

        margin-bottom: 26px;
    }

    .profil-label {
        display: block;

        margin-bottom: 4px;

        color: #a91e2a;

        font-size: 12px;
        font-weight: 800;

        letter-spacing: .9px;
    }

    .profil-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3.4vw, 38px);
        font-weight: 800;

        letter-spacing: -.8px;
    }

    .profil-motto {
        max-width: 560px;

        margin: 10px auto 0;

        color: #596474;

        font-size: 14px;
        font-style: italic;
        line-height: 1.6;
    }


    /* =========================================
       ABOUT
    ========================================= */

    .profil-about {
        max-width: 760px;

        margin: 0 auto 38px;

        text-align: center;
    }

    .profil-about p {
        margin: 0;

        color: #414c5c;

        font-size: 14px;
        line-height: 1.8;
    }


    /* =========================================
       STATS
    ========================================= */

    .profil-stats {
        display: grid;

        grid-template-columns: repeat(4, minmax(0, 1fr));

        gap: 16px;

        margin-bottom: 40px;
    }

    .profil-stat-card {
        display: flex;
        flex-direction: column;

        align-items: center;

        padding: 22px 12px;

        background: #f6f8fb;

        border-radius: 12px;

        text-align: center;
    }

    .profil-stat-card strong {
        color: #123567;

        font-size: 26px;
        font-weight: 800;
    }

    .profil-stat-card span {
        margin-top: 4px;

        color: #667080;

        font-size: 11.5px;
        font-weight: 600;
    }


    /* =========================================
       QUICK LINKS
    ========================================= */

    .profil-links {
        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 16px;

        margin-bottom: 40px;
    }

    .profil-link-card {
        display: flex;
        align-items: center;

        gap: 14px;

        padding: 18px 20px;

        background: #ffffff;

        border: 1px solid #e2e6eb;
        border-radius: 12px;

        text-decoration: none;

        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .profil-link-card:hover {
        border-color: #123b70;

        box-shadow: 0 10px 22px rgba(18, 53, 103, .08);

        transform: translateY(-2px);
    }

    .profil-link-icon {
        flex: 0 0 40px;

        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #123b70;
        background: #eef2f8;

        border-radius: 10px;
    }

    .profil-link-icon svg {
        width: 19px;
        height: 19px;

        stroke: currentColor;
        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .profil-link-text {
        flex: 1;
    }

    .profil-link-text strong {
        display: block;

        color: #142f54;

        font-size: 13.5px;
        font-weight: 700;
    }

    .profil-link-text small {
        display: block;

        margin-top: 2px;

        color: #7c8798;

        font-size: 11px;
    }

    .profil-link-arrow {
        flex: 0 0 16px;

        width: 16px;
        height: 16px;

        color: #b9c4d0;

        stroke: currentColor;
        stroke-width: 1.8;
    }


    /* =========================================
       CTA
    ========================================= */

    .profil-cta {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 26px 30px;

        background: #123567;

        border-radius: 14px;
    }

    .profil-cta-text span {
        display: block;

        color: rgba(255, 255, 255, .68);

        font-size: 11.5px;
    }

    .profil-cta-text h3 {
        margin: 3px 0 0;

        color: #ffffff;

        font-size: 18px;
        font-weight: 800;
    }

    .profil-cta-button {
        display: inline-flex;
        align-items: center;

        gap: 8px;

        min-height: 42px;

        padding: 0 22px;

        color: #ffffff;
        background: #c3202d;

        border-radius: 8px;

        font-size: 12.5px;
        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;

        transition: background .2s ease, transform .2s ease;
    }

    .profil-cta-button:hover {
        background: #a91e2a;

        transform: translateY(-2px);
    }

    .profil-cta-button svg {
        width: 15px;
        height: 15px;

        stroke: currentColor;
        stroke-width: 1.8;
    }


    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 700px) {

        .profil-stats,
        .profil-links {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {

        .profil-section {
            padding: 110px 15px 50px;
        }

        .profil-stats {
            grid-template-columns: 1fr 1fr;

            gap: 10px;
        }

        .profil-links {
            grid-template-columns: 1fr;
        }

        .profil-cta {
            flex-direction: column;

            align-items: flex-start;
        }

        .profil-cta-button {
            width: 100%;

            justify-content: center;
        }
    }
</style>

@endsection
