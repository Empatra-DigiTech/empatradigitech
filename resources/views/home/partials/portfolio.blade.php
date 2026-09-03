<section class="portfolio-section" id="portfolio">

    <div class="portfolio-container">

        {{-- SECTION HEADER --}}
        <div class="portfolio-header">

            <span class="portfolio-label">
                PORTFOLIO KAMI
            </span>

            <h2 class="portfolio-title">
                Project yang Telah Kami Kerjakan
            </h2>

            @php
                $portfolioCategories = $table_portofolio->pluck('layanan')->filter()->unique()->values();
            @endphp

            @if($portfolioCategories->count() > 1)
            {{-- CATEGORY FILTER --}}
            <div class="portfolio-filter">

                <button
                    type="button"
                    class="portfolio-filter-btn active"
                    data-filter="all">
                    Semua
                </button>

                @foreach($portfolioCategories as $cat)
                <button
                    type="button"
                    class="portfolio-filter-btn"
                    data-filter="{{ Str::slug($cat) }}">
                    {{ $cat }}
                </button>
                @endforeach

            </div>
            @endif

        </div>


        @if($table_portofolio->count())
        {{-- PORTFOLIO GRID --}}
        <div class="portfolio-grid">

            @foreach($table_portofolio as $row)
            <article
                class="portfolio-card"
                data-category="{{ $row->layanan ? Str::slug($row->layanan) : '' }}">

                <div class="portfolio-image">
                    <img
                        src="{{ asset('storage/' . $row->image) }}"
                        alt="{{ $row->title }}"
                        loading="lazy">
                </div>

                <div class="portfolio-card-content">

                    <h3>
                        {{ $row->title }}
                    </h3>

                    @if($row->layanan)
                    <span class="portfolio-category">
                        {{ $row->layanan }}
                    </span>
                    @endif

                    <p>
                        @if($row->klien)
                            <strong>Client:</strong> {{ $row->klien }}@if($row->industry) &middot; {{ $row->industry }}@endif
                        @else
                            {{ Str::limit(strip_tags($row->renderTrix('content')), 90) }}
                        @endif
                    </p>

                    <a href="{{ route('home.portofolio.show', $row->id) }}" class="portfolio-detail">
                        Lihat Detail

                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M5 12h13"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>
                    </a>

                </div>

            </article>
            @endforeach

        </div>
        @else
        <p style="text-align:center;color:#667080;font-size:13px;">Belum ada portfolio yang ditambahkan.</p>
        @endif


        {{-- VIEW ALL --}}
        <div class="portfolio-footer">

            <a href="{{ route('home.portofolio.index') }}" class="portfolio-all-btn">
                Lihat Semua Portfolio

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
       PORTFOLIO SECTION
    ========================================= */

    .portfolio-section {
        position: relative;
        width: 100%;
        padding: 65px 20px 70px;

        background: #ffffff;

        overflow: hidden;
    }

    .portfolio-container {
        width: min(1240px, 100%);
        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .portfolio-header {
        text-align: center;
        margin-bottom: 10px;
    }

    .portfolio-label {
        display: block;

        margin-bottom: 4px;

        color: #a91e2a;

        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: 1px;
    }

    .portfolio-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3vw, 36px);
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: -0.8px;
    }


    /* =========================================
       FILTER
    ========================================= */

    .portfolio-filter {
        display: flex;
        justify-content: center;
        align-items: center;

        flex-wrap: wrap;

        gap: 10px;

        margin-top: 12px;
        margin-bottom: 10px;
    }

    .portfolio-filter-btn {
        min-width: 95px;
        height: 30px;

        padding: 0 18px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        color: #123567;
        background: #ffffff;

        border: 1px solid #d7dce2;
        border-radius: 18px;

        font-family: inherit;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;

        transition:
            color .2s ease,
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }

    .portfolio-filter-btn:hover {
        border-color: #123b70;
        transform: translateY(-1px);
    }

    .portfolio-filter-btn.active {
        color: #ffffff;
        background: #123b70;
        border-color: #123b70;
    }


    /* =========================================
       GRID
    ========================================= */

    .portfolio-grid {
        display: grid;

        grid-template-columns: repeat(4, 1fr);

        gap: 25px;

        margin-top: 8px;
    }


    /* =========================================
       CARD
    ========================================= */

    .portfolio-card {
        display: flex;
        flex-direction: column;

        min-width: 0;

        overflow: hidden;

        background: #ffffff;

        border: 1px solid #dfe3e8;
        border-radius: 10px;

        box-shadow: 0 2px 8px rgba(18, 53, 103, .025);

        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .portfolio-card:hover {
        transform: translateY(-4px);

        border-color: #cbd3dd;

        box-shadow: 0 12px 25px rgba(18, 53, 103, .10);
    }


    /* =========================================
       IMAGE
    ========================================= */

    .portfolio-image {
        position: relative;

        width: 100%;
        aspect-ratio: 1.95 / 1;

        overflow: hidden;

        background: #eef2f5;
    }

    .portfolio-image img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition: transform .4s ease;
    }

    .portfolio-card:hover .portfolio-image img {
        transform: scale(1.04);
    }


    /* =========================================
       CARD CONTENT
    ========================================= */

    .portfolio-card-content {
        display: flex;
        flex-direction: column;

        flex: 1;

        padding: 12px 18px 14px;
    }

    .portfolio-card-content h3 {
        margin: 0 0 3px;

        color: #142f54;

        font-size: 15px;
        line-height: 1.3;
        font-weight: 800;
    }

    .portfolio-category {
        display: block;

        margin-bottom: 6px;

        color: #b21e29;

        font-size: 10px;
        line-height: 1.2;
        font-weight: 700;
    }

    .portfolio-card-content p {
        min-height: 38px;

        margin: 0 0 8px;

        color: #596474;

        font-size: 11px;
        line-height: 1.55;
    }


    /* =========================================
       DETAIL LINK
    ========================================= */

    .portfolio-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        width: max-content;

        margin-top: auto;

        color: #123b70;

        font-size: 11px;
        font-weight: 700;

        text-decoration: none;

        transition: color .2s ease;
    }

    .portfolio-detail svg {
        width: 14px;
        height: 14px;

        stroke: currentColor;
        stroke-width: 1.8;

        transition: transform .2s ease;
    }

    .portfolio-detail:hover {
        color: #a91e2a;
    }

    .portfolio-detail:hover svg {
        transform: translateX(4px);
    }


    /* =========================================
       FOOTER BUTTON
    ========================================= */

    .portfolio-footer {
        display: flex;
        justify-content: center;

        margin-top: 10px;
    }

    .portfolio-all-btn {
        min-height: 35px;

        padding: 0 24px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        color: #123567;
        background: #ffffff;

        border: 1px solid #d5dbe2;
        border-radius: 8px;

        font-size: 11px;
        font-weight: 700;

        text-decoration: none;

        transition:
            color .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .portfolio-all-btn svg {
        width: 15px;
        height: 15px;

        stroke: currentColor;
        stroke-width: 1.8;

        transition: transform .2s ease;
    }

    .portfolio-all-btn:hover {
        color: #ffffff;
        background: #123b70;
        border-color: #123b70;

        transform: translateY(-2px);

        box-shadow: 0 6px 15px rgba(18, 59, 112, .15);
    }

    .portfolio-all-btn:hover svg {
        transform: translateX(4px);
    }


    /* =========================================
       FILTER ANIMATION
    ========================================= */

    .portfolio-card.is-hidden {
        display: none;
    }

    .portfolio-card.is-visible {
        animation: portfolioFadeIn .3s ease;
    }

    @keyframes portfolioFadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    /* =========================================
       LARGE TABLET
    ========================================= */

    @media (max-width: 1050px) {

        .portfolio-grid {
            grid-template-columns: repeat(2, 1fr);

            max-width: 800px;

            margin-left: auto;
            margin-right: auto;
        }
    }


    /* =========================================
       TABLET
    ========================================= */

    @media (max-width: 700px) {

        .portfolio-section {
            padding: 55px 16px 60px;
        }

        .portfolio-title {
            font-size: 28px;
        }

        .portfolio-filter {
            gap: 7px;
        }

        .portfolio-filter-btn {
            min-width: auto;
            height: 29px;

            padding: 0 14px;

            font-size: 10px;
        }

        .portfolio-grid {
            gap: 15px;
        }
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 500px) {

        .portfolio-section {
            padding: 45px 14px 50px;
        }

        .portfolio-title {
            font-size: 25px;
        }

        .portfolio-filter {
            display: grid;

            grid-template-columns: repeat(2, 1fr);

            max-width: 330px;

            margin-left: auto;
            margin-right: auto;
        }

        .portfolio-filter-btn {
            width: 100%;
        }

        .portfolio-filter-btn:first-child {
            grid-column: span 2;
        }

        .portfolio-grid {
            grid-template-columns: 1fr;

            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .portfolio-card-content {
            padding: 14px 18px 16px;
        }
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const filterButtons = document.querySelectorAll(
            '.portfolio-filter-btn'
        );

        const portfolioCards = document.querySelectorAll(
            '.portfolio-card'
        );


        filterButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                const filter = this.dataset.filter;


                /* Update active button */
                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                this.classList.add('active');


                /* Filter cards */
                portfolioCards.forEach(function (card) {

                    const category = card.dataset.category;

                    const shouldShow =
                        filter === 'all' ||
                        category === filter;


                    if (shouldShow) {

                        card.classList.remove('is-hidden');

                        /*
                         * Force animation to replay
                         */
                        card.classList.remove('is-visible');

                        void card.offsetWidth;

                        card.classList.add('is-visible');

                    } else {

                        card.classList.remove('is-visible');
                        card.classList.add('is-hidden');

                    }

                });

            });

        });

    });
</script>