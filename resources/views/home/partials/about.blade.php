<section class="about-section" id="about">

    <div class="about-container">

        {{-- =========================================
             SECTION HEADER
        ========================================== --}}
        <div class="about-header">

            <span class="about-label">
                TENTANG KAMI
            </span>

            <h2 class="about-title">
                Partner Digital untuk
                <span>Bisnis Anda</span>
            </h2>

            <p class="about-subtitle">
                Kami membantu bisnis membangun solusi digital yang tepat,
                modern, dan siap berkembang.
            </p>

        </div>


        {{-- =========================================
             ABOUT CONTENT
        ========================================== --}}
        <div class="about-content">

            {{-- LEFT : VISUAL --}}
            <div class="about-visual">

                <div class="about-image-card">

                    <div class="about-image-placeholder">

                        <div class="about-placeholder-content">

                            <div class="about-placeholder-icon">
                                <svg viewBox="0 0 48 48" fill="none">
                                    <rect x="6" y="8" width="36" height="27" rx="3"/>
                                    <path d="M6 15h36"/>
                                    <path d="M12 12h.01"/>
                                    <path d="M16 12h.01"/>
                                    <path d="M20 12h.01"/>
                                    <path d="M14 23h20"/>
                                    <path d="M14 28h13"/>
                                </svg>
                            </div>

                            <strong>
                                Empatra Digitech
                            </strong>

                            <span>
                                Digital Solution Partner
                            </span>

                        </div>

                    </div>


                    {{-- Floating experience card --}}
                    @if(!empty($table_pengaturan->stat_years_experience))
                    <div class="about-experience">

                        <strong>
                            {{ $table_pengaturan->stat_years_experience }}
                        </strong>

                        <span>
                            Tahun<br>
                            Pengalaman
                        </span>

                    </div>
                    @endif


                    {{-- Floating project card --}}
                    @if(!empty($table_pengaturan->stat_projects))
                    <div class="about-project">

                        <span class="about-project-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M4 7h16v13H4z"/>
                                <path d="M8 7V4h8v3"/>
                                <path d="M8 12h8"/>
                                <path d="M8 16h5"/>
                            </svg>
                        </span>

                        <div>
                            <strong>{{ $table_pengaturan->stat_projects }}</strong>
                            <span>Project Selesai</span>
                        </div>

                    </div>
                    @endif

                </div>

            </div>


            {{-- RIGHT : CONTENT --}}
            <div class="about-text">

                <span class="about-small-title">
                    MENGAPA EMPATRA DIGITECH?
                </span>

                <h3>
                    Membangun solusi digital
                    yang memberikan hasil nyata.
                </h3>

                <p>
                    Empatra Digitech adalah partner pengembangan solusi digital
                    yang membantu bisnis menghadirkan website, aplikasi, dan
                    sistem digital sesuai kebutuhan.
                </p>

                <p>
                    Kami menggabungkan desain yang modern, teknologi yang tepat,
                    serta proses pengembangan yang terstruktur untuk menghasilkan
                    solusi yang tidak hanya menarik secara visual, tetapi juga
                    mudah digunakan dan dapat mendukung perkembangan bisnis.
                </p>


                {{-- VALUES --}}
                <div class="about-values">

                    <div class="about-value">

                        <span class="about-value-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 3 4 6v5c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V6l-8-3Z"/>
                                <path d="m8.5 12 2.2 2.2 4.8-5"/>
                            </svg>
                        </span>

                        <div>
                            <strong>
                                Kualitas Terjamin
                            </strong>

                            <span>
                                Mengutamakan kualitas pada setiap project.
                            </span>
                        </div>

                    </div>


                    <div class="about-value">

                        <span class="about-value-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="8"/>
                                <path d="M12 8v4l2.5 2.5"/>
                            </svg>
                        </span>

                        <div>
                            <strong>
                                Proses Terstruktur
                            </strong>

                            <span>
                                Pengerjaan jelas dari awal hingga selesai.
                            </span>
                        </div>

                    </div>


                    <div class="about-value">

                        <span class="about-value-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 12h14"/>
                                <path d="m13 6 6 6-6 6"/>
                                <path d="M5 6v12"/>
                            </svg>
                        </span>

                        <div>
                            <strong>
                                Solusi Fleksibel
                            </strong>

                            <span>
                                Dapat disesuaikan dengan kebutuhan bisnis.
                            </span>
                        </div>

                    </div>


                    <div class="about-value">

                        <span class="about-value-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M4 12a8 8 0 1 0 2.3-5.7"/>
                                <path d="M4 5v6h6"/>
                                <path d="M12 8v4l3 2"/>
                            </svg>
                        </span>

                        <div>
                            <strong>
                                Support Berkelanjutan
                            </strong>

                            <span>
                                Dukungan setelah project selesai.
                            </span>
                        </div>

                    </div>

                </div>


                {{-- BUTTON --}}
                <a href="#contact" class="about-button">

                    Konsultasi dengan Kami

                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>

                </a>

            </div>

        </div>


        {{-- =========================================
             STATISTICS
        ========================================== --}}
        @php
            $aboutStatIcons = [
                'projects' => '<svg viewBox="0 0 24 24" fill="none"><path d="M5 20V10"/><path d="M12 20V4"/><path d="M19 20v-7"/></svg>',
                'clients' => '<svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.5"/><path d="M3.5 19c.6-3.1 2.4-5 5.5-5s4.9 1.9 5.5 5"/><path d="M14 15c2.8-.3 5 1.1 6 4"/></svg>',
                'years' => '<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8"/><path d="m12 7 1.5 3.5L17 12l-3.5 1.5L12 17l-1.5-3.5L7 12l3.5-1.5L12 7Z"/></svg>',
                'industries' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 3 5 6v5c0 4.5 2.8 7.8 7 9 4.2-1.2 7-4.5 7-9V6l-7-3Z"/><path d="m8.5 12 2.2 2.2 4.8-5"/></svg>',
            ];

            $aboutStats = collect([
                ['key' => 'projects', 'value' => $table_pengaturan->stat_projects ?? null, 'label' => 'Project Selesai'],
                ['key' => 'clients', 'value' => $table_pengaturan->stat_clients ?? null, 'label' => 'Klien Puas'],
                ['key' => 'years', 'value' => $table_pengaturan->stat_years_experience ?? null, 'label' => 'Tahun Pengalaman'],
                ['key' => 'industries', 'value' => $table_pengaturan->stat_industries ?? null, 'label' => 'Industri Terlayani'],
            ])->filter(fn($stat) => !empty($stat['value']))->values();
        @endphp

        @if($aboutStats->count())
        <div class="about-stats">

            @foreach($aboutStats as $stat)
            <div class="about-stat">

                <span class="about-stat-icon">
                    {!! $aboutStatIcons[$stat['key']] !!}
                </span>

                <div>
                    <strong>{{ $stat['value'] }}</strong>
                    <span>{{ $stat['label'] }}</span>
                </div>

            </div>

            @if(!$loop->last)
            <div class="about-stat-divider"></div>
            @endif
            @endforeach

        </div>
        @endif

    </div>

</section>


<style>
    /* =========================================
       ABOUT SECTION
    ========================================== */

    .about-section {
        position: relative;

        width: 100%;

        padding: 75px 20px;

        background: #ffffff;

        overflow: hidden;
    }

    .about-section::before {
        content: "";

        position: absolute;

        width: 300px;
        height: 300px;

        top: -170px;
        left: -130px;

        border-radius: 50%;

        background: rgba(18, 59, 112, .035);

        pointer-events: none;
    }

    .about-section::after {
        content: "";

        position: absolute;

        width: 250px;
        height: 250px;

        right: -140px;
        bottom: -130px;

        border-radius: 50%;

        background: rgba(169, 30, 42, .035);

        pointer-events: none;
    }

    .about-container {
        position: relative;

        z-index: 2;

        width: min(1180px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================== */

    .about-header {
        text-align: center;

        margin-bottom: 45px;
    }

    .about-label {
        display: block;

        margin-bottom: 5px;

        color: #a91e2a;

        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: 1px;
    }

    .about-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3vw, 36px);
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: -.8px;
    }

    .about-title span {
        color: #a91e2a;
    }

    .about-subtitle {
        max-width: 620px;

        margin: 8px auto 0;

        color: #667080;

        font-size: 13px;
        line-height: 1.55;
    }


    /* =========================================
       CONTENT
    ========================================== */

    .about-content {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1.1fr);

        align-items: center;

        gap: 75px;
    }


    /* =========================================
       VISUAL
    ========================================== */

    .about-visual {
        position: relative;

        padding: 20px 30px 30px 15px;
    }

    .about-image-card {
        position: relative;

        min-height: 380px;

        border-radius: 12px;

        overflow: visible;
    }

    .about-image-placeholder {
        position: relative;

        width: 100%;
        height: 380px;

        display: flex;
        align-items: center;
        justify-content: center;

        background:
            linear-gradient(
                135deg,
                #eef3f8 0%,
                #f8fafc 55%,
                #e9eff5 100%
            );

        border: 1px solid #d9e0e7;

        border-radius: 12px;

        box-shadow:
            0 12px 35px rgba(18, 53, 103, .08);
    }

    .about-image-placeholder::before {
        content: "";

        position: absolute;

        width: 180px;
        height: 180px;

        top: -55px;
        right: -55px;

        border-radius: 50%;

        background: #123b70;

        opacity: .09;
    }

    .about-image-placeholder::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        bottom: -45px;
        left: -35px;

        border-radius: 50%;

        background: #c3202d;

        opacity: .08;
    }

    .about-placeholder-content {
        position: relative;

        z-index: 2;

        display: flex;
        flex-direction: column;

        align-items: center;

        text-align: center;
    }

    .about-placeholder-icon {
        width: 76px;
        height: 76px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        background: #123b70;

        border-radius: 14px;

        box-shadow:
            0 10px 20px rgba(18, 59, 112, .18);
    }

    .about-placeholder-icon svg {
        width: 42px;
        height: 42px;

        stroke: #ffffff;

        stroke-width: 1.5;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .about-placeholder-content strong {
        color: #123567;

        font-size: 21px;
        line-height: 1.3;
        font-weight: 800;
    }

    .about-placeholder-content > span {
        margin-top: 5px;

        color: #758191;

        font-size: 11px;
    }


    /* =========================================
       FLOATING EXPERIENCE
    ========================================== */

    .about-experience {
        position: absolute;

        top: 0;
        right: 0;

        width: 125px;
        min-height: 90px;

        display: flex;
        align-items: center;

        gap: 9px;

        padding: 14px;

        background: #123b70;

        border-radius: 9px;

        box-shadow:
            0 10px 25px rgba(18, 59, 112, .20);
    }

    .about-experience strong {
        color: #ffffff;

        font-size: 28px;
        line-height: 1;
        font-weight: 800;
    }

    .about-experience span {
        color: rgba(255, 255, 255, .78);

        font-size: 9px;
        line-height: 1.35;
    }


    /* =========================================
       FLOATING PROJECT
    ========================================== */

    .about-project {
        position: absolute;

        left: 0;
        bottom: 0;

        display: flex;
        align-items: center;

        gap: 10px;

        padding: 13px 17px;

        background: #ffffff;

        border: 1px solid #dfe4ea;

        border-radius: 9px;

        box-shadow:
            0 9px 25px rgba(18, 53, 103, .10);
    }

    .about-project-icon {
        width: 35px;
        height: 35px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #eef3f8;

        border-radius: 7px;
    }

    .about-project-icon svg {
        width: 18px;
        height: 18px;

        stroke: #123b70;

        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .about-project div {
        display: flex;
        flex-direction: column;

        gap: 2px;
    }

    .about-project strong {
        color: #123567;

        font-size: 15px;
        line-height: 1;
        font-weight: 800;
    }

    .about-project div span {
        color: #7a8491;

        font-size: 9px;
    }


    /* =========================================
       TEXT
    ========================================== */

    .about-text {
        max-width: 550px;
    }

    .about-small-title {
        display: block;

        margin-bottom: 8px;

        color: #a91e2a;

        font-size: 11px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: .7px;
    }

    .about-text h3 {
        margin: 0 0 15px;

        color: #123567;

        font-size: clamp(24px, 2.5vw, 31px);
        line-height: 1.25;
        font-weight: 800;

        letter-spacing: -.5px;
    }

    .about-text > p {
        margin: 0 0 11px;

        color: #657181;

        font-size: 12px;
        line-height: 1.75;
    }


    /* =========================================
       VALUES
    ========================================== */

    .about-values {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 16px 20px;

        margin-top: 23px;
        margin-bottom: 25px;
    }

    .about-value {
        display: flex;
        align-items: flex-start;

        gap: 9px;
    }

    .about-value-icon {
        width: 31px;
        height: 31px;

        flex: 0 0 31px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #eef3f8;

        border-radius: 6px;
    }

    .about-value-icon svg {
        width: 16px;
        height: 16px;

        stroke: #123b70;

        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .about-value div {
        display: flex;
        flex-direction: column;

        gap: 3px;
    }

    .about-value strong {
        color: #123567;

        font-size: 11px;
        line-height: 1.25;
        font-weight: 800;
    }

    .about-value div span {
        color: #7a8491;

        font-size: 9px;
        line-height: 1.4;
    }


    /* =========================================
       BUTTON
    ========================================== */

    .about-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        min-height: 40px;

        padding: 0 18px;

        color: #ffffff;

        background: #123b70;

        border-radius: 6px;

        font-size: 11px;
        line-height: 1;
        font-weight: 700;

        text-decoration: none;

        transition:
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .about-button svg {
        width: 14px;
        height: 14px;

        stroke: currentColor;

        stroke-width: 1.8;

        stroke-linecap: round;
        stroke-linejoin: round;

        transition: transform .2s ease;
    }

    .about-button:hover {
        background: #0d2d57;

        transform: translateY(-2px);

        box-shadow:
            0 7px 15px rgba(18, 59, 112, .18);
    }

    .about-button:hover svg {
        transform: translateX(3px);
    }


    /* =========================================
       STATISTICS
    ========================================== */

    .about-stats {
        display: flex;
        align-items: center;
        justify-content: center;

        margin-top: 55px;

        padding: 22px 25px;

        background: #f7f9fb;

        border: 1px solid #e0e5ea;

        border-radius: 9px;
    }

    .about-stat {
        display: flex;
        align-items: center;

        gap: 11px;

        min-width: 190px;
    }

    .about-stat-icon {
        width: 38px;
        height: 38px;

        flex: 0 0 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #ffffff;

        border: 1px solid #dce2e8;

        border-radius: 7px;
    }

    .about-stat-icon svg {
        width: 19px;
        height: 19px;

        stroke: #123b70;

        stroke-width: 1.6;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .about-stat div {
        display: flex;
        flex-direction: column;

        gap: 3px;
    }

    .about-stat strong {
        color: #123567;

        font-size: 18px;
        line-height: 1;
        font-weight: 800;
    }

    .about-stat div span {
        color: #788391;

        font-size: 9px;
        line-height: 1.2;
    }

    .about-stat-divider {
        width: 1px;
        height: 38px;

        margin: 0 20px;

        background: #dce2e8;
    }


    /* =========================================
       TABLET
    ========================================== */

    @media (max-width: 950px) {

        .about-content {
            gap: 45px;
        }

        .about-visual {
            padding-right: 20px;
        }

        .about-image-placeholder {
            height: 340px;
        }

        .about-image-card {
            min-height: 340px;
        }

        .about-stat {
            min-width: 0;
        }

        .about-stat-divider {
            margin: 0 14px;
        }
    }


    /* =========================================
       MOBILE
    ========================================== */

    @media (max-width: 750px) {

        .about-section {
            padding: 60px 18px 65px;
        }

        .about-header {
            margin-bottom: 30px;
        }

        .about-content {
            grid-template-columns: 1fr;

            gap: 45px;
        }

        .about-visual {
            width: min(520px, 100%);

            margin: 0 auto;

            padding: 15px 25px 25px 10px;
        }

        .about-image-placeholder {
            height: 320px;
        }

        .about-image-card {
            min-height: 320px;
        }

        .about-text {
            max-width: none;
        }

        .about-stats {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;

            padding: 20px;
        }

        .about-stat-divider {
            display: none;
        }
    }


    /* =========================================
       SMALL MOBILE
    ========================================== */

    @media (max-width: 500px) {

        .about-section {
            padding: 50px 15px 55px;
        }

        .about-title {
            font-size: 27px;
        }

        .about-subtitle {
            font-size: 12px;
        }

        .about-visual {
            padding: 12px 18px 25px 6px;
        }

        .about-image-placeholder {
            height: 275px;
        }

        .about-image-card {
            min-height: 275px;
        }

        .about-placeholder-icon {
            width: 62px;
            height: 62px;
        }

        .about-placeholder-icon svg {
            width: 34px;
            height: 34px;
        }

        .about-placeholder-content strong {
            font-size: 18px;
        }

        .about-experience {
            width: 108px;

            min-height: 78px;

            padding: 11px;
        }

        .about-experience strong {
            font-size: 23px;
        }

        .about-experience span {
            font-size: 8px;
        }

        .about-project {
            padding: 10px 12px;
        }

        .about-project-icon {
            width: 30px;
            height: 30px;
        }

        .about-project strong {
            font-size: 13px;
        }

        .about-text h3 {
            font-size: 23px;
        }

        .about-values {
            grid-template-columns: 1fr;

            gap: 13px;
        }

        .about-button {
            width: 100%;
        }

        .about-stats {
            grid-template-columns: 1fr 1fr;

            gap: 17px 12px;

            margin-top: 40px;

            padding: 17px 12px;
        }

        .about-stat {
            gap: 7px;
        }

        .about-stat-icon {
            width: 32px;
            height: 32px;

            flex-basis: 32px;
        }

        .about-stat-icon svg {
            width: 16px;
            height: 16px;
        }

        .about-stat strong {
            font-size: 15px;
        }

        .about-stat div span {
            font-size: 8px;
        }
    }
</style>