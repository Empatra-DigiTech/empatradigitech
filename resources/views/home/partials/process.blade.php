<section class="progress-section" id="progress">

    <div class="progress-container">

        {{-- SECTION HEADER --}}
        <div class="progress-header">
            <span class="progress-label">
                PROSES KERJA KAMI
            </span>

            <h2 class="progress-title">
                Tahapan Pengerjaan Project
            </h2>
        </div>


        {{-- PROCESS TIMELINE --}}
        <div class="progress-timeline">

            {{-- CONNECTING LINE --}}
            <div class="progress-line"></div>


            {{-- STEP 1 --}}
            <div class="progress-step">

                <div class="progress-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <circle cx="22" cy="19" r="7"/>
                        <path d="M10 37c1.5-7 5.5-11 12-11s10.5 4 12 11"/>
                        <path d="M33 31l6 6"/>
                        <circle cx="31" cy="29" r="6"/>
                    </svg>
                </div>

                <h3>
                    1. Konsultasi
                </h3>

                <p>
                    Kami mendengarkan kebutuhan<br>
                    dan tujuan project Anda.
                </p>

            </div>


            {{-- STEP 2 --}}
            <div class="progress-step">

                <div class="progress-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="13" y="7" width="22" height="34" rx="3"/>
                        <path d="M19 7h10"/>
                        <path d="M18 16h12"/>
                        <path d="M18 22h12"/>
                        <path d="M18 28h7"/>
                        <path d="m27 29 3 3 5-6"/>
                    </svg>
                </div>

                <h3>
                    2. Perencanaan
                </h3>

                <p>
                    Menyusun rencana kerja, fitur,<br>
                    dan timeline project.
                </p>

            </div>


            {{-- STEP 3 --}}
            <div class="progress-step">

                <div class="progress-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="10" y="9" width="28" height="29" rx="3"/>
                        <path d="M10 16h28"/>
                        <path d="M17 22h14"/>
                        <path d="M17 27h10"/>
                        <path d="M17 32h7"/>
                    </svg>
                </div>

                <h3>
                    3. Desain & Development
                </h3>

                <p>
                    Proses desain UI/UX dan<br>
                    pengembangan sistem.
                </p>

            </div>


            {{-- STEP 4 --}}
            <div class="progress-step">

                <div class="progress-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="12" y="7" width="24" height="34" rx="3"/>
                        <path d="M18 7h12"/>
                        <path d="m20 25 3 3 6-7"/>
                        <path d="M18 34h12"/>
                    </svg>
                </div>

                <h3>
                    4. Testing
                </h3>

                <p>
                    Pengujian sistem untuk<br>
                    memastikan kualitas terbaik.
                </p>

            </div>


            {{-- STEP 5 --}}
            <div class="progress-step">

                <div class="progress-icon">
                    <svg viewBox="0 0 48 48" fill="none">
                        <rect x="13" y="7" width="22" height="34" rx="3"/>
                        <path d="M19 7h10"/>
                        <circle cx="24" cy="23" r="6"/>
                        <path d="M24 19v4l3 2"/>
                        <path d="M19 34h10"/>
                    </svg>
                </div>

                <h3>
                    5. Release & Support
                </h3>

                <p>
                    Project dirilis dan kami berikan<br>
                    support berkelanjutan.
                </p>

            </div>

        </div>

    </div>

</section>


<style>
    /* =========================================
       PROGRESS / WORK PROCESS
    ========================================= */

    .progress-section {
        position: relative;

        width: 100%;

        padding: 24px 20px 25px;

        background: #07386f;

        overflow: hidden;
    }

    /*
     * Subtle inner gradient to reproduce the
     * dark blue appearance from the reference.
     */
    .progress-section::before {
        content: "";

        position: absolute;
        inset: 0;

        background:
            radial-gradient(
                circle at 15% 50%,
                rgba(255, 255, 255, .035),
                transparent 35%
            ),
            linear-gradient(
                90deg,
                rgba(0, 0, 0, .08),
                transparent 25%,
                transparent 75%,
                rgba(0, 0, 0, .08)
            );

        pointer-events: none;
    }

    .progress-container {
        position: relative;
        z-index: 2;

        width: min(1160px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .progress-header {
        margin-bottom: 7px;
    }

    .progress-label {
        display: block;

        margin-bottom: 2px;

        color: #d82735;

        font-size: 10px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: .8px;
    }

    .progress-title {
        margin: 0;

        color: #ffffff;

        font-size: 20px;
        line-height: 1.15;
        font-weight: 800;

        letter-spacing: -.3px;
    }


    /* =========================================
       TIMELINE
    ========================================= */

    .progress-timeline {
        position: relative;

        display: grid;

        grid-template-columns:
            repeat(5, 1fr);

        column-gap: 10px;

        padding-top: 0;
    }


    /* =========================================
       CONNECTING LINE
    ========================================= */

    .progress-line {
        position: absolute;

        /*
         * Centered through the icon circles.
         */
        left: 7%;
        right: 7%;
        top: 29px;

        height: 0;

        border-top: 2px dotted rgba(255, 255, 255, .7);

        z-index: 0;
    }


    /* =========================================
       STEP
    ========================================= */

    .progress-step {
        position: relative;

        z-index: 1;

        display: flex;
        flex-direction: column;
        align-items: center;

        text-align: center;
    }


    /* =========================================
       ICON
    ========================================= */

    .progress-icon {
        position: relative;

        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 5px;

        background: #ffffff;

        border: 2px solid rgba(255, 255, 255, .25);

        border-radius: 50%;

        box-shadow:
            0 2px 7px rgba(0, 0, 0, .12);
    }

    .progress-icon svg {
        width: 29px;
        height: 29px;

        stroke: #c31f2b;

        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }


    /* =========================================
       STEP TITLE
    ========================================= */

    .progress-step h3 {
        margin: 0 0 3px;

        color: #ffffff;

        font-size: 12px;
        line-height: 1.3;
        font-weight: 800;
    }


    /* =========================================
       STEP DESCRIPTION
    ========================================= */

    .progress-step p {
        margin: 0;

        color: rgba(255, 255, 255, .88);

        font-size: 9px;
        line-height: 1.55;
        font-weight: 400;
    }


    /* =========================================
       HOVER
    ========================================= */

    .progress-step {
        transition: transform .25s ease;
    }

    .progress-step:hover {
        transform: translateY(-3px);
    }

    .progress-step:hover .progress-icon {
        box-shadow:
            0 5px 14px rgba(0, 0, 0, .2);
    }


    /* =========================================
       LARGE TABLET
    ========================================= */

    @media (max-width: 900px) {

        .progress-section {
            padding: 30px 20px;
        }

        .progress-timeline {
            grid-template-columns:
                repeat(5, minmax(130px, 1fr));

            overflow-x: auto;

            padding-bottom: 8px;
        }

        .progress-step {
            min-width: 130px;
        }

        .progress-line {
            left: 65px;
            right: 65px;
        }
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 600px) {

        .progress-section {
            padding: 40px 20px;
        }

        .progress-header {
            text-align: center;

            margin-bottom: 30px;
        }

        .progress-label {
            font-size: 10px;
        }

        .progress-title {
            font-size: 22px;
        }

        .progress-timeline {
            display: flex;
            flex-direction: column;

            gap: 24px;

            padding: 0;
            overflow: visible;
        }

        /*
         * Vertical connecting line on mobile.
         */
        .progress-line {
            left: 24px;
            right: auto;

            top: 24px;
            bottom: 24px;

            width: 0;
            height: auto;

            border-top: 0;
            border-left: 2px dotted rgba(255, 255, 255, .65);
        }

        .progress-step {
            display: grid;

            grid-template-columns: 48px 1fr;

            column-gap: 15px;

            min-width: 0;

            align-items: center;

            text-align: left;
        }

        .progress-icon {
            grid-row: span 2;

            margin: 0;
        }

        .progress-step h3 {
            align-self: end;

            margin: 0 0 3px;

            font-size: 13px;
        }

        .progress-step p {
            align-self: start;

            font-size: 10px;
        }

        .progress-step p br {
            display: none;
        }
    }
</style>