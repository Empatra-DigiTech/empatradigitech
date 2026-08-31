<section class="testimonial-section" id="testimonials">

    <div class="testimonial-container">

        {{-- SECTION HEADER --}}
        <div class="testimonial-header">

            <span class="testimonial-label">
                TESTIMONI KLIEN
            </span>

            <h2 class="testimonial-title">
                Apa Kata Klien Kami
            </h2>

            <p class="testimonial-description">
                Kepercayaan dan kepuasan klien menjadi bagian penting dari setiap project yang kami kerjakan.
            </p>

        </div>


        {{-- TESTIMONIALS --}}
        <div class="testimonial-grid">

            {{-- TESTIMONIAL 1 --}}
            <article class="testimonial-card">

                <div class="testimonial-stars" aria-label="5 out of 5 stars">
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                </div>

                <div class="testimonial-quote">
                    <svg viewBox="0 0 48 48" fill="none">
                        <path d="M14 11H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h6l-5 8h7l7-12V15a4 4 0 0 0-4-4h-5Z"/>
                        <path d="M38 11h-6a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h6l-5 8h7l7-12V15a4 4 0 0 0-4-4h-5Z"/>
                    </svg>
                </div>

                <p class="testimonial-text">
                    "Pelayanan sangat baik dan komunikasinya mudah. Website
                    yang dibuat sesuai dengan kebutuhan bisnis kami dan
                    tampilannya juga sangat profesional."
                </p>

                <div class="testimonial-client">

                    <div class="testimonial-avatar">
                        AS
                    </div>

                    <div class="testimonial-client-info">
                        <strong>Andi Setiawan</strong>
                        <span>Business Owner</span>
                    </div>

                </div>

            </article>


            {{-- TESTIMONIAL 2 --}}
            <article class="testimonial-card testimonial-card-featured">

                <div class="testimonial-stars" aria-label="5 out of 5 stars">
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                </div>

                <div class="testimonial-quote">
                    <svg viewBox="0 0 48 48" fill="none">
                        <path d="M14 11H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h6l-5 8h7l7-12V15a4 4 0 0 0-4-4h-5Z"/>
                        <path d="M38 11h-6a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h6l-5 8h7l7-12V15a4 4 0 0 0-4-4h-5Z"/>
                    </svg>
                </div>

                <p class="testimonial-text">
                    "Sangat terbantu dengan tim Empatra Digitech. Dari tahap
                    konsultasi sampai development semuanya dijelaskan dengan
                    jelas. Hasil akhirnya melebihi ekspektasi kami."
                </p>

                <div class="testimonial-client">

                    <div class="testimonial-avatar">
                        RP
                    </div>

                    <div class="testimonial-client-info">
                        <strong>Rina Pratiwi</strong>
                        <span>Project Manager</span>
                    </div>

                </div>

            </article>


            {{-- TESTIMONIAL 3 --}}
            <article class="testimonial-card">

                <div class="testimonial-stars" aria-label="5 out of 5 stars">
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                </div>

                <div class="testimonial-quote">
                    <svg viewBox="0 0 48 48" fill="none">
                        <path d="M14 11H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h6l-5 8h7l7-12V15a4 4 0 0 0-4-4h-5Z"/>
                        <path d="M38 11h-6a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h6l-5 8h7l7-12V15a4 4 0 0 0-4-4h-5Z"/>
                    </svg>
                </div>

                <p class="testimonial-text">
                    "Timnya responsif dan sangat membantu ketika ada perubahan
                    kebutuhan. Sistem berjalan dengan baik dan support setelah
                    project selesai juga sangat memuaskan."
                </p>

                <div class="testimonial-client">

                    <div class="testimonial-avatar">
                        DF
                    </div>

                    <div class="testimonial-client-info">
                        <strong>Dimas Firmansyah</strong>
                        <span>Business Owner</span>
                    </div>

                </div>

            </article>

        </div>


        {{-- TRUST INDICATOR --}}
        <div class="testimonial-trust">

            <div class="testimonial-trust-rating">

                <strong>5.0</strong>

                <div>
                    <div class="testimonial-trust-stars">
                        ★★★★★
                    </div>

                    <span>Berdasarkan kepuasan klien</span>
                </div>

            </div>


            <div class="testimonial-trust-divider"></div>


            <div class="testimonial-trust-item">

                <strong>30+</strong>

                <span>Klien Puas</span>

            </div>


            <div class="testimonial-trust-divider"></div>


            <div class="testimonial-trust-item">

                <strong>50+</strong>

                <span>Project Selesai</span>

            </div>

        </div>

    </div>

</section>


<style>
    /* =========================================
       TESTIMONIAL SECTION
    ========================================= */

    .testimonial-section {
        position: relative;

        width: 100%;

        padding: 70px 20px 75px;

        background: #f8fafc;

        overflow: hidden;
    }

    /*
     * Subtle decorative background elements
     */
    .testimonial-section::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        left: -150px;
        top: 80px;

        border-radius: 50%;

        background: rgba(18, 59, 112, .035);

        pointer-events: none;
    }

    .testimonial-section::after {
        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        right: -120px;
        bottom: 30px;

        border-radius: 50%;

        background: rgba(169, 30, 42, .035);

        pointer-events: none;
    }

    .testimonial-container {
        position: relative;

        z-index: 2;

        width: min(1180px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .testimonial-header {
        text-align: center;

        margin-bottom: 32px;
    }

    .testimonial-label {
        display: block;

        margin-bottom: 4px;

        color: #a91e2a;

        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: 1px;
    }

    .testimonial-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3vw, 36px);
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: -.8px;
    }

    .testimonial-description {
        max-width: 620px;

        margin: 7px auto 0;

        color: #667080;

        font-size: 13px;
        line-height: 1.55;
    }


    /* =========================================
       TESTIMONIAL GRID
    ========================================= */

    .testimonial-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 22px;
    }


    /* =========================================
       CARD
    ========================================= */

    .testimonial-card {
        position: relative;

        display: flex;
        flex-direction: column;

        min-height: 285px;

        padding: 25px 27px 23px;

        background: #ffffff;

        border: 1px solid #dfe4ea;
        border-radius: 10px;

        box-shadow:
            0 3px 10px rgba(18, 53, 103, .035);

        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);

        border-color: #c7d0db;

        box-shadow:
            0 14px 30px rgba(18, 53, 103, .09);
    }

    /*
     * Featured card
     */
    .testimonial-card-featured {
        border-color: #123b70;

        box-shadow:
            0 5px 18px rgba(18, 59, 112, .08);
    }

    .testimonial-card-featured::before {
        content: "";

        position: absolute;

        left: 0;
        right: 0;
        top: 0;

        height: 3px;

        background: #123b70;

        border-radius: 10px 10px 0 0;
    }


    /* =========================================
       STARS
    ========================================= */

    .testimonial-stars {
        display: flex;

        align-items: center;

        gap: 2px;

        margin-bottom: 9px;

        color: #d5a927;

        font-size: 15px;
        line-height: 1;
    }


    /* =========================================
       QUOTE ICON
    ========================================= */

    .testimonial-quote {
        position: absolute;

        right: 23px;
        top: 23px;

        width: 34px;
        height: 34px;

        opacity: .13;
    }

    .testimonial-quote svg {
        width: 100%;
        height: 100%;

        stroke: #123b70;

        stroke-width: 1.5;

        stroke-linecap: round;
        stroke-linejoin: round;
    }


    /* =========================================
       TESTIMONIAL TEXT
    ========================================= */

    .testimonial-text {
        margin: 0;

        color: #465466;

        font-size: 13px;
        line-height: 1.75;
    }


    /* =========================================
       CLIENT
    ========================================= */

    .testimonial-client {
        display: flex;
        align-items: center;

        gap: 11px;

        margin-top: auto;
        padding-top: 20px;
    }

    .testimonial-avatar {
        width: 40px;
        height: 40px;

        flex: 0 0 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #ffffff;
        background: #123b70;

        border-radius: 50%;

        font-size: 11px;
        font-weight: 800;
    }

    .testimonial-client-info {
        display: flex;
        flex-direction: column;

        gap: 3px;
    }

    .testimonial-client-info strong {
        color: #123567;

        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;
    }

    .testimonial-client-info span {
        color: #7a8491;

        font-size: 10px;
        line-height: 1.2;
    }


    /* =========================================
       TRUST INDICATOR
    ========================================= */

    .testimonial-trust {
        display: flex;
        align-items: center;
        justify-content: center;

        margin: 30px auto 0;

        width: max-content;
        max-width: 100%;

        padding: 14px 28px;

        background: #ffffff;

        border: 1px solid #e0e5ea;
        border-radius: 9px;

        box-shadow:
            0 3px 10px rgba(18, 53, 103, .025);
    }

    .testimonial-trust-rating {
        display: flex;
        align-items: center;

        gap: 10px;
    }

    .testimonial-trust-rating > strong {
        color: #123567;

        font-size: 24px;
        line-height: 1;
        font-weight: 800;
    }

    .testimonial-trust-rating > div {
        display: flex;
        flex-direction: column;

        gap: 2px;
    }

    .testimonial-trust-stars {
        color: #d5a927;

        font-size: 12px;
        line-height: 1;
        letter-spacing: 1px;
    }

    .testimonial-trust-rating span {
        color: #788391;

        font-size: 9px;
        line-height: 1.2;
    }

    .testimonial-trust-divider {
        width: 1px;
        height: 34px;

        margin: 0 25px;

        background: #dfe3e8;
    }

    .testimonial-trust-item {
        display: flex;
        flex-direction: column;

        gap: 2px;

        text-align: center;
    }

    .testimonial-trust-item strong {
        color: #123567;

        font-size: 17px;
        line-height: 1;
        font-weight: 800;
    }

    .testimonial-trust-item span {
        color: #788391;

        font-size: 9px;
        line-height: 1.2;
    }


    /* =========================================
       TABLET
    ========================================= */

    @media (max-width: 850px) {

        .testimonial-section {
            padding: 60px 18px 65px;
        }

        .testimonial-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .testimonial-card:last-child {
            grid-column: span 2;

            max-width: 50%;

            width: 100%;

            margin: 0 auto;
        }
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 600px) {

        .testimonial-section {
            padding: 50px 15px 55px;
        }

        .testimonial-header {
            margin-bottom: 25px;
        }

        .testimonial-title {
            font-size: 27px;
        }

        .testimonial-description {
            font-size: 12px;
        }

        .testimonial-grid {
            grid-template-columns: 1fr;

            gap: 14px;
        }

        .testimonial-card {
            min-height: 250px;

            padding: 22px 22px 20px;
        }

        .testimonial-card:last-child {
            grid-column: auto;

            max-width: none;
        }

        .testimonial-text {
            font-size: 12px;

            line-height: 1.7;
        }

        .testimonial-trust {
            width: 100%;

            padding: 14px 12px;
        }

        .testimonial-trust-divider {
            margin: 0 13px;
        }

        .testimonial-trust-rating > strong {
            font-size: 20px;
        }

        .testimonial-trust-item strong {
            font-size: 15px;
        }
    }


    @media (max-width: 400px) {

        .testimonial-trust {
            display: grid;

            grid-template-columns:
                1fr 1px 1fr 1px 1fr;

            gap: 8px;

            padding: 13px 8px;
        }

        .testimonial-trust-divider {
            margin: 0;
        }

        .testimonial-trust-rating {
            gap: 5px;
        }

        .testimonial-trust-rating > strong {
            font-size: 18px;
        }

        .testimonial-trust-rating span {
            font-size: 8px;
        }

        .testimonial-trust-item strong {
            font-size: 14px;
        }

        .testimonial-trust-item span {
            font-size: 8px;
        }
    }
</style>