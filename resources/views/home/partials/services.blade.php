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

        @php
            $serviceCategoryOrder = \App\Models\Layanan::kategoriOptions();

            $serviceIcons = [
                'Website' => '<svg viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="18"/><path d="M6 24h36"/><path d="M24 6c5 5 7 11 7 18s-2 13-7 18"/><path d="M24 6c-5 5-7 11-7 18s2 13 7 18"/><path d="M9 15h30"/><path d="M9 33h30"/></svg>',
                'Web Application' => '<svg viewBox="0 0 48 48" fill="none"><rect x="9" y="7" width="30" height="34" rx="3"/><path d="M9 14h30"/><path d="M15 23h8"/><path d="M15 29h5"/><path d="m28 22 2 2 4-5"/></svg>',
                'Mobile Application' => '<svg viewBox="0 0 48 48" fill="none"><rect x="14" y="5" width="20" height="38" rx="4"/><path d="M20 10h8"/><circle cx="24" cy="36" r="1.5"/><path d="m24 17 4 4-4 4-4-4 4-4Z"/></svg>',
                'Custom Software' => '<svg viewBox="0 0 48 48" fill="none"><rect x="8" y="9" width="13" height="13" rx="2"/><rect x="27" y="9" width="13" height="13" rx="2"/><rect x="8" y="27" width="13" height="13" rx="2"/><rect x="27" y="27" width="13" height="13" rx="2"/><path d="M14.5 15.5h.01"/><path d="M33.5 15.5h.01"/><path d="M14.5 33.5h.01"/><path d="M33.5 33.5h.01"/></svg>',
                'Lainnya' => '<svg viewBox="0 0 48 48" fill="none"><path d="M25 8a13 13 0 0 0-10 21l-6 6 4 4 6-6a13 13 0 0 0 21-10l-8 5-7-7 5-8Z"/><path d="m31 17 3-3"/><circle cx="24" cy="25" r="5"/></svg>',
            ];

            $serviceGrouped = $table_layanan->groupBy(function ($item) use ($serviceCategoryOrder) {
                return in_array($item->kategori, $serviceCategoryOrder) ? $item->kategori : 'Lainnya';
            });

            $serviceCategories = collect($serviceCategoryOrder)->filter(fn($cat) => $serviceGrouped->has($cat))->values();
            if ($serviceGrouped->has('Lainnya')) {
                $serviceCategories->push('Lainnya');
            }
        @endphp

        @if($table_layanan->count())

            @if($serviceCategories->count() > 1)
            {{-- CATEGORY FILTER --}}
            <div class="services-filter">

                <button type="button" class="services-filter-btn active" data-service-filter="all">
                    Semua
                </button>

                @foreach($serviceCategories as $cat)
                <button type="button" class="services-filter-btn" data-service-filter="{{ Str::slug($cat) }}">
                    {{ $cat }}
                </button>
                @endforeach

            </div>
            @endif

            {{-- SERVICES GRID --}}
            <div class="services-grid">

                @foreach($serviceCategories as $cat)
                    @foreach($serviceGrouped[$cat] as $row)
                    <article class="service-card" data-service-category="{{ Str::slug($cat) }}">

                        <div class="service-icon">
                            {!! $serviceIcons[$cat] ?? $serviceIcons['Lainnya'] !!}
                        </div>

                        <h3>{{ $row->title }}</h3>

                        <p>
                            {{ Str::limit(strip_tags($row->description ?? ''), 100) }}
                        </p>

                        <a href="{{ route('home.layanan.show', $row->id) }}" class="service-link">
                            Selengkapnya
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 12h13"/>
                                <path d="m13 6 6 6-6 6"/>
                            </svg>
                        </a>

                    </article>
                    @endforeach
                @endforeach

            </div>

        @else
            <p style="text-align:center;color:#667080;font-size:13px;">Belum ada layanan yang ditambahkan.</p>
        @endif

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
       CATEGORY FILTER
    ========================================= */

    .services-filter {
        display: flex;
        justify-content: center;
        align-items: center;

        flex-wrap: wrap;

        gap: 10px;

        margin-bottom: 25px;
    }

    .services-filter-btn {
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

    .services-filter-btn:hover {
        border-color: #123b70;
        transform: translateY(-1px);
    }

    .services-filter-btn.active {
        color: #ffffff;
        background: #123b70;
        border-color: #123b70;
    }


    /* =========================================
       GRID
    ========================================= */

    .services-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 22px;
    }

    .service-card.is-hidden {
        display: none;
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

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const section = document.querySelector('.services-section');
        if (!section) return;

        const filterButtons = section.querySelectorAll('.services-filter-btn');
        const serviceCards = section.querySelectorAll('.service-card');

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {

                const filter = this.dataset.serviceFilter;

                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                serviceCards.forEach(function (card) {
                    const category = card.dataset.serviceCategory;
                    const shouldShow = filter === 'all' || category === filter;
                    card.classList.toggle('is-hidden', !shouldShow);
                });

            });
        });

    });
</script>