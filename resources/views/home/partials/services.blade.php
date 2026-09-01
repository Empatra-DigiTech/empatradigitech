<section class="services-section" id="services">

    <div class="services-container">

        {{-- SECTION HEADER --}}
        <div class="services-header">
            <span class="services-label">LAYANAN KAMI</span>

            <h2 class="services-title">
                Solusi Digital yang Kami Tawarkan
            </h2>

            <p class="services-description">
                Kami membantu bisnis Anda tumbuh dengan solusi digital yang tepat dan inovatif.
            </p>
        </div>


        {{-- SERVICES GRID --}}
        <div class="services-grid">

            {{-- WEBSITE --}}
            <article class="service-card">

                <div class="service-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="18"/>
                        <path d="M6 24h36"/>
                        <path d="M24 6c5 5 7 11 7 18s-2 13-7 18"/>
                        <path d="M24 6c-5 5-7 11-7 18s2 13 7 18"/>
                        <path d="M9 15h30"/>
                        <path d="M9 33h30"/>
                    </svg>
                </div>

                <h3>Pembuatan Website</h3>

                <p>
                    Website profesional, responsif, dan SEO friendly untuk semua
                    kebutuhan bisnis Anda.
                </p>

                <a href="#contact" class="service-link">
                    Selengkapnya
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>

            </article>


            {{-- WEB APPLICATION --}}
            <article class="service-card">

                <div class="service-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="9" y="7" width="30" height="34" rx="3"/>
                        <path d="M9 14h30"/>
                        <path d="M15 23h8"/>
                        <path d="M15 29h5"/>
                        <path d="m28 22 2 2 4-5"/>
                    </svg>
                </div>

                <h3>Aplikasi Web</h3>

                <p>
                    Sistem web custom dengan fitur lengkap untuk meningkatkan
                    efisiensi bisnis.
                </p>

                <a href="#contact" class="service-link">
                    Selengkapnya
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>

            </article>


            {{-- MOBILE APPLICATION --}}
            <article class="service-card">

                <div class="service-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="14" y="5" width="20" height="38" rx="4"/>
                        <path d="M20 10h8"/>
                        <circle cx="24" cy="36" r="1.5"/>
                        <path d="m24 17 4 4-4 4-4-4 4-4Z"/>
                    </svg>
                </div>

                <h3>Aplikasi Mobile</h3>

                <p>
                    Aplikasi Android & iOS dengan performa optimal dan desain
                    modern.
                </p>

                <a href="#contact" class="service-link">
                    Selengkapnya
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>

            </article>


            {{-- CUSTOM SYSTEM --}}
            <article class="service-card">

                <div class="service-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="8" y="9" width="13" height="13" rx="2"/>
                        <rect x="27" y="9" width="13" height="13" rx="2"/>
                        <rect x="8" y="27" width="13" height="13" rx="2"/>
                        <rect x="27" y="27" width="13" height="13" rx="2"/>
                        <path d="M14.5 15.5h.01"/>
                        <path d="M33.5 15.5h.01"/>
                        <path d="M14.5 33.5h.01"/>
                        <path d="M33.5 33.5h.01"/>
                    </svg>
                </div>

                <h3>Sistem Custom</h3>

                <p>
                    Solusi sistem informasi sesuai kebutuhan bisnis yang spesifik.
                </p>

                <a href="#contact" class="service-link">
                    Selengkapnya
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>

            </article>


            {{-- MAINTENANCE --}}
            <article class="service-card">

                <div class="service-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <path d="M25 8a13 13 0 0 0-10 21l-6 6 4 4 6-6a13 13 0 0 0 21-10l-8 5-7-7 5-8Z"/>
                        <path d="m31 17 3-3"/>
                        <circle cx="24" cy="25" r="5"/>
                    </svg>
                </div>

                <h3>Maintenance</h3>

                <p>
                    Layanan pemeliharaan, update, dan pengembangan sistem yang
                    berkelanjutan.
                </p>

                <a href="#contact" class="service-link">
                    Selengkapnya
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>

            </article>

        </div>

    </div>

</section>


<style>
    /* =========================================
       SERVICES
    ========================================= */

    .services-section {
        position: relative;
        width: 100%;
        padding: 70px 20px 80px;
        background: #ffffff;
        overflow: hidden;
    }

    .services-container {
        width: min(1240px, 100%);
        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .services-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .services-label {
        display: block;

        margin-bottom: 5px;

        color: #a91e2a;

        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .services-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3vw, 36px);
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.8px;
    }

    .services-description {
        max-width: 620px;

        margin: 6px auto 0;

        color: #667080;

        font-size: 13px;
        line-height: 1.5;
    }


    /* =========================================
       GRID
    ========================================= */

    .services-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 22px;
    }


    /* =========================================
       CARD
    ========================================= */

    .service-card {
        min-height: 265px;

        display: flex;
        flex-direction: column;
        align-items: center;

        padding: 17px 25px 18px;

        background: #ffffff;

        border: 1px solid #e0e4e9;
        border-radius: 9px;

        text-align: center;

        box-shadow: 0 2px 8px rgba(18, 53, 103, 0.025);

        transition:
            transform .25s ease,
            border-color .25s ease,
            box-shadow .25s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);

        border-color: #c5cfdb;

        box-shadow: 0 12px 28px rgba(18, 53, 103, 0.09);
    }


    /* =========================================
       ICON
    ========================================= */

    .service-icon {
        width: 53px;
        height: 53px;

        margin-bottom: 7px;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .service-icon svg {
        width: 47px;
        height: 47px;

        stroke: #123b70;
        stroke-width: 1.6;
        stroke-linecap: round;
        stroke-linejoin: round;
    }


    /* =========================================
       TITLE
    ========================================= */

    .service-card h3 {
        margin: 0 0 7px;

        color: #123567;

        font-size: 14px;
        line-height: 1.3;
        font-weight: 800;
    }


    /* =========================================
       DESCRIPTION
    ========================================= */

    .service-card p {
        min-height: 62px;

        margin: 0;

        color: #596474;

        font-size: 12px;
        line-height: 1.65;
    }


    /* =========================================
       LINK
    ========================================= */

    .service-link {
        margin-top: auto;

        display: inline-flex;
        align-items: center;
        gap: 7px;

        color: #123b70;

        font-size: 11px;
        line-height: 1;
        font-weight: 700;

        text-decoration: none;
    }

    .service-link svg {
        width: 15px;
        height: 15px;

        stroke: currentColor;
        stroke-width: 1.8;

        transition: transform .2s ease;
    }

    .service-link:hover {
        color: #a91e2a;
    }

    .service-link:hover svg {
        transform: translateX(4px);
    }


    /* =========================================
       LARGE TABLET
    ========================================= */

    @media (max-width: 1100px) {

        .services-grid {
            grid-template-columns: repeat(3, 1fr);
            max-width: 900px;
            margin: 0 auto;
        }

        .service-card {
            min-height: 250px;
        }
    }


    /* =========================================
       TABLET
    ========================================= */

    @media (max-width: 700px) {

        .services-section {
            padding: 55px 16px 60px;
        }

        .services-header {
            margin-bottom: 25px;
        }

        .services-title {
            font-size: 27px;
        }

        .services-description {
            font-size: 12px;
        }

        .services-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .service-card {
            min-height: 245px;
            padding: 18px 18px;
        }
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 480px) {

        .services-section {
            padding: 45px 14px 50px;
        }

        .services-title {
            font-size: 24px;
        }

        .services-description {
            max-width: 330px;
        }

        .services-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .service-card {
            min-height: 220px;
            padding: 22px 30px 20px;
        }

        .service-icon {
            margin-bottom: 8px;
        }

        .service-card p {
            max-width: 290px;
        }
    }
</style>