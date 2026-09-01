<section class="carousel-section" id="carousel">

    <div class="carousel-container">

        {{-- =========================================
             SECTION INTRO
        ========================================== --}}
        <div class="carousel-heading">

            <span class="carousel-label">
                OUR WORK
            </span>

            <h2>
                Solusi Digital untuk
                <span>Berbagai Kebutuhan</span>
            </h2>

        </div>


        {{-- =========================================
             CAROUSEL
        ========================================== --}}
        <div class="carousel-wrapper">

            <button
                type="button"
                class="carousel-arrow carousel-prev"
                aria-label="Previous slide">

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 18 9 12l6-6"/>
                </svg>

            </button>


            <div class="carousel-viewport">

                <div class="carousel-track">

                    {{-- SLIDE 1 --}}
                    <article class="carousel-card">

                        <div class="carousel-card-image">

                            {{-- Replace with your actual image --}}
                            <img
                                src="{{ asset('images/carousel/website.jpg') }}"
                                alt="Website Development"
                                loading="lazy">

                            <div class="carousel-card-overlay"></div>

                            <span class="carousel-number">
                                01
                            </span>

                        </div>

                        <div class="carousel-card-content">

                            <span class="carousel-category">
                                WEB DEVELOPMENT
                            </span>

                            <h3>
                                Website Profesional
                            </h3>

                            <p>
                                Website modern dan responsive untuk
                                memperkuat kehadiran digital bisnis Anda.
                            </p>

                            <a href="#portfolio" class="carousel-link">
                                Lihat Project

                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12h13"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    {{-- SLIDE 2 --}}
                    <article class="carousel-card">

                        <div class="carousel-card-image">

                            <img
                                src="{{ asset('images/carousel/application.jpg') }}"
                                alt="Web Application"
                                loading="lazy">

                            <div class="carousel-card-overlay"></div>

                            <span class="carousel-number">
                                02
                            </span>

                        </div>

                        <div class="carousel-card-content">

                            <span class="carousel-category">
                                APPLICATION
                            </span>

                            <h3>
                                Aplikasi Web Custom
                            </h3>

                            <p>
                                Sistem berbasis web yang dirancang sesuai
                                workflow dan kebutuhan bisnis Anda.
                            </p>

                            <a href="#services" class="carousel-link">
                                Lihat Layanan

                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12h13"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    {{-- SLIDE 3 --}}
                    <article class="carousel-card">

                        <div class="carousel-card-image">

                            <img
                                src="{{ asset('images/carousel/mobile.jpg') }}"
                                alt="Mobile Application"
                                loading="lazy">

                            <div class="carousel-card-overlay"></div>

                            <span class="carousel-number">
                                03
                            </span>

                        </div>

                        <div class="carousel-card-content">

                            <span class="carousel-category">
                                MOBILE APP
                            </span>

                            <h3>
                                Aplikasi Mobile
                            </h3>

                            <p>
                                Pengalaman aplikasi mobile yang intuitif,
                                cepat, dan mudah digunakan.
                            </p>

                            <a href="#services" class="carousel-link">
                                Lihat Layanan

                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12h13"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    {{-- SLIDE 4 --}}
                    <article class="carousel-card">

                        <div class="carousel-card-image">

                            <img
                                src="{{ asset('images/carousel/system.jpg') }}"
                                alt="Custom Digital System"
                                loading="lazy">

                            <div class="carousel-card-overlay"></div>

                            <span class="carousel-number">
                                04
                            </span>

                        </div>

                        <div class="carousel-card-content">

                            <span class="carousel-category">
                                CUSTOM SYSTEM
                            </span>

                            <h3>
                                Sistem Digital Custom
                            </h3>

                            <p>
                                Solusi digital terintegrasi untuk membantu
                                bisnis bekerja lebih efektif dan efisien.
                            </p>

                            <a href="#services" class="carousel-link">
                                Lihat Layanan

                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12h13"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    {{-- SLIDE 5 --}}
                    <article class="carousel-card">

                        <div class="carousel-card-image">

                            <img
                                src="{{ asset('images/carousel/maintenance.jpg') }}"
                                alt="Website Maintenance"
                                loading="lazy">

                            <div class="carousel-card-overlay"></div>

                            <span class="carousel-number">
                                05
                            </span>

                        </div>

                        <div class="carousel-card-content">

                            <span class="carousel-category">
                                SUPPORT
                            </span>

                            <h3>
                                Maintenance & Support
                            </h3>

                            <p>
                                Dukungan berkelanjutan untuk menjaga sistem
                                digital tetap aman dan optimal.
                            </p>

                            <a href="#contact" class="carousel-link">
                                Konsultasi

                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12h13"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>

                        </div>

                    </article>

                </div>

            </div>


            <button
                type="button"
                class="carousel-arrow carousel-next"
                aria-label="Next slide">

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="m9 18 6-6-6-6"/>
                </svg>

            </button>

        </div>


        {{-- =========================================
             CONTROLS
        ========================================== --}}
        <div class="carousel-controls">

            <div class="carousel-progress">
                <span class="carousel-progress-current">01</span>

                <div class="carousel-progress-line">
                    <span></span>
                </div>

                <span class="carousel-progress-total">05</span>
            </div>


            <div class="carousel-dots">

                <button
                    type="button"
                    class="carousel-dot active"
                    aria-label="Go to slide 1">
                </button>

                <button
                    type="button"
                    class="carousel-dot"
                    aria-label="Go to slide 2">
                </button>

                <button
                    type="button"
                    class="carousel-dot"
                    aria-label="Go to slide 3">
                </button>

                <button
                    type="button"
                    class="carousel-dot"
                    aria-label="Go to slide 4">
                </button>

                <button
                    type="button"
                    class="carousel-dot"
                    aria-label="Go to slide 5">
                </button>

            </div>

        </div>

    </div>

</section>


<style>
    /* =========================================
       CAROUSEL SECTION
    ========================================== */

    .carousel-section {
        position: relative;

        width: 100%;

        padding: 55px 20px 65px;

        background: #f7f9fb;

        overflow: hidden;
    }

    .carousel-container {
        width: min(1180px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADING
    ========================================== */

    .carousel-heading {
        margin-bottom: 28px;

        text-align: center;
    }

    .carousel-label {
        display: block;

        margin-bottom: 5px;

        color: #a91e2a;

        font-size: 11px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: 1px;
    }

    .carousel-heading h2 {
        margin: 0;

        color: #123567;

        font-size: clamp(26px, 3vw, 34px);
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: -.7px;
    }

    .carousel-heading h2 span {
        color: #a91e2a;
    }


    /* =========================================
       WRAPPER
    ========================================== */

    .carousel-wrapper {
        position: relative;

        display: flex;
        align-items: center;

        gap: 18px;
    }

    .carousel-viewport {
        width: 100%;

        overflow: hidden;
    }

    .carousel-track {
        display: flex;

        gap: 18px;

        transition:
            transform .45s cubic-bezier(.22, .61, .36, 1);

        will-change: transform;
    }


    /* =========================================
       CARD
    ========================================== */

    .carousel-card {
        flex: 0 0 calc((100% - 36px) / 3);

        min-width: 0;

        background: #ffffff;

        border: 1px solid #dfe4ea;

        border-radius: 9px;

        overflow: hidden;

        box-shadow:
            0 5px 18px rgba(18, 53, 103, .045);

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .carousel-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 12px 28px rgba(18, 53, 103, .10);
    }


    /* =========================================
       CARD IMAGE
    ========================================== */

    .carousel-card-image {
        position: relative;

        height: 205px;

        overflow: hidden;

        background: #e8edf2;
    }

    .carousel-card-image img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            transform .45s ease;
    }

    .carousel-card:hover .carousel-card-image img {
        transform: scale(1.04);
    }

    .carousel-card-overlay {
        position: absolute;

        inset: 0;

        background:
            linear-gradient(
                to bottom,
                rgba(8, 47, 93, .02),
                rgba(8, 47, 93, .42)
            );

        pointer-events: none;
    }

    .carousel-number {
        position: absolute;

        top: 14px;
        right: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 38px;
        height: 28px;

        color: #ffffff;

        background: rgba(8, 47, 93, .82);

        border: 1px solid rgba(255, 255, 255, .18);

        border-radius: 5px;

        font-size: 10px;
        line-height: 1;
        font-weight: 800;

        letter-spacing: .5px;

        backdrop-filter: blur(5px);
    }


    /* =========================================
       CARD CONTENT
    ========================================== */

    .carousel-card-content {
        padding: 19px 19px 20px;
    }

    .carousel-category {
        display: block;

        margin-bottom: 6px;

        color: #a91e2a;

        font-size: 9px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: .7px;
    }

    .carousel-card-content h3 {
        margin: 0 0 7px;

        color: #123567;

        font-size: 17px;
        line-height: 1.3;
        font-weight: 800;
    }

    .carousel-card-content p {
        min-height: 51px;

        margin: 0 0 14px;

        color: #687484;

        font-size: 10px;
        line-height: 1.65;
    }


    /* =========================================
       LINK
    ========================================== */

    .carousel-link {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        color: #123b70;

        font-size: 10px;
        line-height: 1;
        font-weight: 800;

        text-decoration: none;

        transition:
            color .2s ease;
    }

    .carousel-link svg {
        width: 13px;
        height: 13px;

        stroke: currentColor;

        stroke-width: 1.8;

        stroke-linecap: round;
        stroke-linejoin: round;

        transition:
            transform .2s ease;
    }

    .carousel-link:hover {
        color: #a91e2a;
    }

    .carousel-link:hover svg {
        transform: translateX(3px);
    }


    /* =========================================
       ARROWS
    ========================================== */

    .carousel-arrow {
        width: 40px;
        height: 40px;

        flex: 0 0 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #123b70;

        background: #ffffff;

        border: 1px solid #d8e0e8;

        border-radius: 50%;

        cursor: pointer;

        box-shadow:
            0 4px 12px rgba(18, 53, 103, .07);

        transition:
            color .2s ease,
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }

    .carousel-arrow svg {
        width: 17px;
        height: 17px;

        stroke: currentColor;

        stroke-width: 1.8;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .carousel-arrow:hover:not(:disabled) {
        color: #ffffff;

        background: #123b70;

        border-color: #123b70;

        transform: translateY(-1px);
    }

    .carousel-arrow:disabled {
        opacity: .35;

        cursor: default;

        box-shadow: none;
    }


    /* =========================================
       CONTROLS
    ========================================== */

    .carousel-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-top: 22px;
    }

    .carousel-progress {
        display: flex;
        align-items: center;

        gap: 9px;

        color: #123567;

        font-size: 9px;
        line-height: 1;
        font-weight: 800;
    }

    .carousel-progress-line {
        width: 90px;
        height: 2px;

        overflow: hidden;

        background: #d8dee5;

        border-radius: 2px;
    }

    .carousel-progress-line span {
        display: block;

        width: 20%;
        height: 100%;

        background: #a91e2a;

        border-radius: inherit;

        transition:
            width .4s ease;
    }

    .carousel-progress-total {
        color: #8a939f;
    }


    /* =========================================
       DOTS
    ========================================== */

    .carousel-dots {
        display: flex;
        align-items: center;

        gap: 6px;
    }

    .carousel-dot {
        width: 7px;
        height: 7px;

        padding: 0;

        background: #cdd4dc;

        border: 0;

        border-radius: 50%;

        cursor: pointer;

        transition:
            width .25s ease,
            background .25s ease,
            border-radius .25s ease;
    }

    .carousel-dot.active {
        width: 22px;

        background: #123b70;

        border-radius: 5px;
    }


    /* =========================================
       TABLET
    ========================================== */

    @media (max-width: 950px) {

        .carousel-card {
            flex-basis: calc((100% - 18px) / 2);
        }

        .carousel-card-image {
            height: 190px;
        }
    }


    /* =========================================
       MOBILE
    ========================================== */

    @media (max-width: 650px) {

        .carousel-section {
            padding: 50px 15px 55px;
        }

        .carousel-heading {
            margin-bottom: 22px;
        }

        .carousel-heading h2 {
            font-size: 26px;
        }

        .carousel-wrapper {
            gap: 8px;
        }

        .carousel-card {
            flex-basis: 100%;
        }

        .carousel-card-image {
            height: 210px;
        }

        .carousel-card-content {
            padding: 17px;
        }

        .carousel-arrow {
            position: absolute;

            z-index: 5;

            top: 80px;

            width: 34px;
            height: 34px;

            flex-basis: 34px;
        }

        .carousel-prev {
            left: 10px;
        }

        .carousel-next {
            right: 10px;
        }

        .carousel-controls {
            margin-top: 18px;
        }
    }


    /* =========================================
       SMALL MOBILE
    ========================================== */

    @media (max-width: 400px) {

        .carousel-card-image {
            height: 190px;
        }

        .carousel-card-content h3 {
            font-size: 16px;
        }

        .carousel-card-content p {
            font-size: 10px;
        }

        .carousel-progress-line {
            width: 60px;
        }
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const section = document.querySelector('.carousel-section');

        if (!section) return;


        const track = section.querySelector('.carousel-track');
        const cards = section.querySelectorAll('.carousel-card');

        const prevButton = section.querySelector('.carousel-prev');
        const nextButton = section.querySelector('.carousel-next');

        const dots = section.querySelectorAll('.carousel-dot');

        const currentNumber =
            section.querySelector('.carousel-progress-current');

        const progress =
            section.querySelector('.carousel-progress-line span');


        let currentIndex = 0;


        function getVisibleCards() {

            if (window.innerWidth <= 650) {
                return 1;
            }

            if (window.innerWidth <= 950) {
                return 2;
            }

            return 3;
        }


        function getMaxIndex() {

            return Math.max(
                0,
                cards.length - getVisibleCards()
            );
        }


        function updateCarousel() {

            const visibleCards = getVisibleCards();

            const cardWidth = cards[0].getBoundingClientRect().width;

            const gap =
                parseFloat(
                    window.getComputedStyle(track).gap
                ) || 0;


            /*
             * Move the track.
             */
            track.style.transform =
                `translateX(-${currentIndex * (cardWidth + gap)}px)`;


            /*
             * Update number.
             */
            currentNumber.textContent =
                String(currentIndex + 1).padStart(2, '0');


            /*
             * Update progress.
             */
            const progressValue =
                ((currentIndex + 1) /
                (getMaxIndex() + 1)) * 100;

            progress.style.width =
                `${Math.min(progressValue, 100)}%`;


            /*
             * Update dots.
             */
            dots.forEach(function (dot, index) {

                dot.classList.toggle(
                    'active',
                    index === currentIndex
                );

            });


            /*
             * Disable arrows at boundaries.
             */
            prevButton.disabled =
                currentIndex <= 0;

            nextButton.disabled =
                currentIndex >= getMaxIndex();

        }


        /*
         * Next.
         */
        nextButton.addEventListener('click', function () {

            if (currentIndex < getMaxIndex()) {

                currentIndex++;

                updateCarousel();

            }

        });


        /*
         * Previous.
         */
        prevButton.addEventListener('click', function () {

            if (currentIndex > 0) {

                currentIndex--;

                updateCarousel();

            }

        });


        /*
         * Dots.
         */
        dots.forEach(function (dot, index) {

            dot.addEventListener('click', function () {

                currentIndex =
                    Math.min(index, getMaxIndex());

                updateCarousel();

            });

        });


        /*
         * Responsive recalculation.
         */
        window.addEventListener('resize', function () {

            currentIndex =
                Math.min(
                    currentIndex,
                    getMaxIndex()
                );

            updateCarousel();

        });


        /*
         * Touch / swipe support.
         */
        let touchStartX = 0;
        let touchEndX = 0;


        track.addEventListener(
            'touchstart',
            function (event) {

                touchStartX =
                    event.changedTouches[0].screenX;

            },
            { passive: true }
        );


        track.addEventListener(
            'touchend',
            function (event) {

                touchEndX =
                    event.changedTouches[0].screenX;

                const difference =
                    touchStartX - touchEndX;


                if (Math.abs(difference) < 50) {
                    return;
                }


                if (difference > 0) {

                    if (currentIndex < getMaxIndex()) {
                        currentIndex++;
                    }

                } else {

                    if (currentIndex > 0) {
                        currentIndex--;
                    }

                }


                updateCarousel();

            },
            { passive: true }
        );


        /*
         * Initial state.
         */
        updateCarousel();

    });
</script>