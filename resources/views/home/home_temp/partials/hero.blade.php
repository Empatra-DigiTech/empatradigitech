<section class="hero-section">
    <div class="hero-bg-shape hero-bg-blue"></div>
    <div class="hero-bg-shape hero-bg-red"></div>

    <div class="hero-container">

        {{-- LEFT CONTENT --}}
        <div class="hero-content">

            <div class="hero-badge">
                Solusi Digital untuk Bisnis Anda
            </div>

            <h1 class="hero-title">
                Wujudkan Ide,<br>
                Bangun Solusi Digital
                <span>Bersama Empatra Digitech</span>
            </h1>

            <p class="hero-description">
                Kami menyediakan jasa pembuatan website, aplikasi web,
                mobile app, dan sistem digital yang profesional, modern,
                dan terpercaya.
            </p>

            <div class="hero-features">
                <div class="hero-feature">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8 12l2.5 2.5L16 9"/>
                        </svg>
                    </span>
                    <span>Desain Modern & Responsif</span>
                </div>

                <div class="hero-feature">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8 12l2.5 2.5L16 9"/>
                        </svg>
                    </span>
                    <span>Performa Cepat & Optimal</span>
                </div>

                <div class="hero-feature">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8 12l2.5 2.5L16 9"/>
                        </svg>
                    </span>
                    <span>Keamanan Terjamin</span>
                </div>

                <div class="hero-feature">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M8 12l2.5 2.5L16 9"/>
                        </svg>
                    </span>
                    <span>Support & Maintenance</span>
                </div>
            </div>

            <div class="hero-actions">
                <a href="#contact" class="hero-btn hero-btn-primary">
                    Konsultasi Gratis

                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M20 11.5a8 8 0 0 1-8 8 8.4 8.4 0 0 1-3.7-.85L4 20l1.35-4.05A8.4 8.4 0 0 1 4 12.2a8 8 0 1 1 16 .3Z"/>
                        <path d="M8.5 10.5c.25 1.2 1.8 2.75 3 3 .6.12 1.2-.2 1.65-.7l.55-.6c.15-.17.4-.2.6-.08l1.35.75"/>
                    </svg>
                </a>

                <a href="#portfolio" class="hero-btn hero-btn-secondary">
                    Lihat Portfolio

                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>
            </div>

        </div>


        {{-- RIGHT VISUAL --}}
        <div class="hero-visual">

            {{-- Decorative dots --}}
            <div class="hero-dots hero-dots-left">
                @for ($i = 0; $i < 25; $i++)
                    <span></span>
                @endfor
            </div>

            <div class="hero-dots hero-dots-top">
                @for ($i = 0; $i < 36; $i++)
                    <span></span>
                @endfor
            </div>

            {{-- Device visual --}}
            <div class="hero-device-wrapper">
                <img
                    src="{{ asset('images/hero-reference.png') }}"
                    alt="Empatra Digitech Digital Solutions"
                    class="hero-reference-image"
                >
            </div>

        </div>

    </div>


    {{-- STATISTICS --}}
    <div class="hero-stats">

        <div class="hero-stat">
            <div class="stat-icon">
                <svg viewBox="0 0 32 32" fill="none">
                    <rect x="8" y="6" width="16" height="22" rx="2"/>
                    <path d="M12 4h8v4h-8z"/>
                    <path d="M12 13h8"/>
                    <path d="M12 18h8"/>
                    <path d="M12 23h5"/>
                </svg>
            </div>

            <div class="stat-content">
                <strong>50+</strong>
                <span>Project Selesai</span>
            </div>
        </div>


        <div class="hero-stat">
            <div class="stat-icon">
                <svg viewBox="0 0 32 32" fill="none">
                    <circle cx="16" cy="9" r="5"/>
                    <circle cx="7" cy="13" r="4"/>
                    <circle cx="25" cy="13" r="4"/>
                    <path d="M7 28c0-5 3.5-8 9-8s9 3 9 8"/>
                    <path d="M2 27c0-3.5 2-6 5.5-6"/>
                    <path d="M30 27c0-3.5-2-6-5.5-6"/>
                </svg>
            </div>

            <div class="stat-content">
                <strong>30+</strong>
                <span>Klien Puas</span>
            </div>
        </div>


        <div class="hero-stat">
            <div class="stat-icon">
                <svg viewBox="0 0 32 32" fill="none">
                    <circle cx="16" cy="13" r="9"/>
                    <path d="m11 21-2 8 7-4 7 4-2-8"/>
                    <path d="m16 8 1.5 3 3.5.5-2.5 2.5.5 3.5-3-1.5-3 1.5.5-3.5-2.5-2.5 3.5-.5L16 8Z"/>
                </svg>
            </div>

            <div class="stat-content">
                <strong>5+</strong>
                <span>Tahun Pengalaman</span>
            </div>
        </div>


        <div class="hero-stat">
            <div class="stat-icon">
                <svg viewBox="0 0 32 32" fill="none">
                    <path d="M16 3 27 7v8c0 7-4.5 11.5-11 14-6.5-2.5-11-7-11-14V7l11-4Z"/>
                    <path d="m11 16 3.2 3.2L21 12"/>
                </svg>
            </div>

            <div class="stat-content">
                <strong>100%</strong>
                <span>Komitmen Kualitas</span>
            </div>
        </div>

    </div>

</section>


<style>
    :root {
        --hero-blue: #07356d;
        --hero-blue-dark: #062b59;
        --hero-red: #d7192d;
        --hero-text: #12345b;
        --hero-light: #ffffff;
    }

    .hero-section {
        position: relative;
        min-height: 680px;
        overflow: hidden;
        background: #fff;
        font-family: Arial, Helvetica, sans-serif;
    }

    /* =========================
       BACKGROUND
    ========================= */

    .hero-bg-shape {
        position: absolute;
        pointer-events: none;
        z-index: 0;
    }

    .hero-bg-blue {
        width: 720px;
        height: 720px;
        right: -250px;
        top: -330px;
        background: var(--hero-blue);
        border-radius: 50%;
    }

    .hero-bg-red {
        width: 600px;
        height: 600px;
        right: -70px;
        top: -170px;
        background: var(--hero-red);
        border-radius: 50%;
    }

    .hero-container {
        position: relative;
        z-index: 2;

        width: min(1180px, calc(100% - 40px));
        min-height: 590px;
        margin: 0 auto;

        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
    }


    /* =========================
       CONTENT
    ========================= */

    .hero-content {
        position: relative;
        z-index: 5;
        padding: 65px 0 80px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;

        padding: 7px 16px;
        margin-bottom: 14px;

        color: #fff;
        background: var(--hero-blue);

        border-radius: 20px;

        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1px;
    }

    .hero-title {
        margin: 0;
        max-width: 610px;

        color: var(--hero-text);

        font-size: clamp(36px, 4vw, 54px);
        line-height: 1.05;
        letter-spacing: -1.8px;
        font-weight: 800;
    }

    .hero-title span {
        display: block;
        margin-top: 4px;
        color: #ad2029;
    }

    .hero-description {
        max-width: 590px;
        margin: 20px 0 22px;

        color: #465568;

        font-size: 15px;
        line-height: 1.65;
    }


    /* =========================
       FEATURES
    ========================= */

    .hero-features {
        display: grid;
        grid-template-columns: repeat(2, max-content);
        gap: 11px 55px;

        margin-bottom: 25px;
    }

    .hero-feature {
        display: flex;
        align-items: center;
        gap: 8px;

        color: #304052;
        font-size: 13px;
        font-weight: 500;
    }

    .feature-icon {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
    }

    .feature-icon svg {
        width: 100%;
        height: 100%;
        stroke: var(--hero-blue);
        stroke-width: 2;
    }


    /* =========================
       BUTTONS
    ========================= */

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .hero-btn {
        min-height: 45px;
        padding: 0 20px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;

        border-radius: 8px;

        text-decoration: none;
        font-size: 13px;
        font-weight: 600;

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .hero-btn:hover {
        transform: translateY(-2px);
    }

    .hero-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        stroke-width: 1.8;
    }

    .hero-btn-primary {
        color: #fff;
        background: var(--hero-blue);
        box-shadow: 0 7px 18px rgba(7, 53, 109, .16);
    }

    .hero-btn-primary:hover {
        background: var(--hero-blue-dark);
    }

    .hero-btn-secondary {
        color: var(--hero-blue);
        background: #fff;
        border: 1px solid #cbd2da;
    }

    .hero-btn-secondary:hover {
        border-color: var(--hero-blue);
    }


    /* =========================
       VISUAL
    ========================= */

    .hero-visual {
        position: relative;
        height: 100%;
        min-height: 580px;
    }

    .hero-device-wrapper {
        position: absolute;
        z-index: 3;

        top: 20px;
        right: -120px;

        width: 760px;
        height: 470px;

        overflow: hidden;
    }

    /*
     * The uploaded reference image contains the exact
     * laptop + mobile composition from the design.
     *
     * This crops the right side of that image so the
     * text portion isn't duplicated.
     */
    .hero-reference-image {
        position: absolute;

        width: 1360px;
        max-width: none;
        height: 483px;

        top: 0;
        left: -570px;

        object-fit: cover;
        object-position: center;
    }


    /* =========================
       DOTS
    ========================= */

    .hero-dots {
        position: absolute;
        z-index: 4;

        display: grid;
        grid-template-columns: repeat(5, 4px);
        gap: 7px;
    }

    .hero-dots span {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--hero-blue);
        opacity: .55;
    }

    .hero-dots-left {
        left: -30px;
        top: 115px;
    }

    .hero-dots-top {
        right: 10px;
        top: 35px;

        grid-template-columns: repeat(6, 4px);
    }


    /* =========================
       STATISTICS
    ========================= */

    .hero-stats {
        position: absolute;
        z-index: 10;

        left: 50%;
        bottom: 8px;
        transform: translateX(-50%);

        width: min(1135px, calc(100% - 40px));
        min-height: 76px;

        display: grid;
        grid-template-columns: repeat(4, 1fr);

        padding: 0 15px;

        background: var(--hero-blue);

        border-radius: 9px;

        box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
    }

    .hero-stat {
        position: relative;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 15px;
    }

    .hero-stat:not(:last-child)::after {
        content: "";

        position: absolute;
        right: 0;
        top: 18px;

        width: 1px;
        height: 40px;

        background: rgba(255,255,255,.25);
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
    }

    .stat-icon svg {
        width: 100%;
        height: 100%;

        stroke: #fff;
        stroke-width: 1.6;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-content strong {
        color: #fff;

        font-size: 23px;
        line-height: 1;
        font-weight: 700;
    }

    .stat-content span {
        margin-top: 5px;

        color: rgba(255,255,255,.85);

        font-size: 11px;
        font-weight: 500;
    }


    /* =========================
       TABLET
    ========================= */

    @media (max-width: 1000px) {

        .hero-section {
            min-height: auto;
        }

        .hero-container {
            grid-template-columns: 1fr;
            padding-bottom: 115px;
        }

        .hero-content {
            padding: 60px 0 20px;
            text-align: center;
        }

        .hero-title,
        .hero-description {
            margin-left: auto;
            margin-right: auto;
        }

        .hero-features {
            justify-content: center;
            text-align: left;
        }

        .hero-actions {
            justify-content: center;
        }

        .hero-visual {
            position: absolute;
            inset: 0;
            opacity: .25;
        }

        .hero-device-wrapper {
            right: -220px;
            top: 90px;
        }

        .hero-stats {
            bottom: 15px;
        }
    }


    /* =========================
       MOBILE
    ========================= */

    @media (max-width: 650px) {

        .hero-container {
            width: min(100% - 28px, 1180px);
            padding-bottom: 185px;
        }

        .hero-content {
            padding-top: 45px;
        }

        .hero-badge {
            font-size: 10px;
        }

        .hero-title {
            font-size: 34px;
            letter-spacing: -1px;
        }

        .hero-description {
            font-size: 13px;
            line-height: 1.55;
        }

        .hero-features {
            grid-template-columns: 1fr;
            gap: 9px;
            width: max-content;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            flex-direction: column;
            gap: 10px;
        }

        .hero-btn {
            width: 220px;
        }

        .hero-bg-blue {
            width: 450px;
            height: 450px;
            right: -300px;
            top: -160px;
        }

        .hero-bg-red {
            width: 400px;
            height: 400px;
            right: -210px;
            top: -90px;
        }

        .hero-device-wrapper {
            display: none;
        }

        .hero-dots {
            display: none;
        }

        .hero-stats {
            width: calc(100% - 28px);
            grid-template-columns: repeat(2, 1fr);
            min-height: 140px;
            padding: 8px 5px;
        }

        .hero-stat {
            justify-content: flex-start;
            padding-left: 12px;
            gap: 8px;
        }

        .hero-stat:nth-child(2)::after {
            display: none;
        }

        .hero-stat:nth-child(-n+2)::after {
            top: auto;
            bottom: 0;
            right: 8px;
            width: calc(100% - 20px);
            height: 1px;
        }

        .stat-icon {
            width: 30px;
            height: 30px;
            flex-basis: 30px;
        }

        .stat-content strong {
            font-size: 18px;
        }

        .stat-content span {
            font-size: 9px;
        }
    }
</style>