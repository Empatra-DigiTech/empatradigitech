<section class="pricing-section" id="pricing">

    <div class="pricing-container">

        {{-- SECTION HEADER --}}
        <div class="pricing-header">

            <span class="pricing-label">
                PAKET HARGA
            </span>

            <h2 class="pricing-title">
                Paket Website & Aplikasi
            </h2>

            <p class="pricing-description">
                Pilih paket yang sesuai dengan kebutuhan bisnis Anda.
            </p>


            {{-- CATEGORY SWITCH --}}
            <div class="pricing-switch">

                <button
                    type="button"
                    class="pricing-switch-btn active"
                    data-pricing-type="website">
                    Website
                </button>

                <button
                    type="button"
                    class="pricing-switch-btn"
                    data-pricing-type="application">
                    Aplikasi
                </button>

                {{-- FIXED: menu navbar "Kalkulator" sudah ada dan mengarah ke sini
                     lewat class nav-open-calculator-tab, tapi sebelumnya tidak ada
                     satupun markup kalkulator yang dirender di halaman ini. Tab ini
                     hanya muncul kalau admin sudah mengisi minimal satu layanan di
                     panel Kalkulator. --}}
                @if($calculator_services->count())
                <button
                    type="button"
                    class="pricing-switch-btn"
                    data-pricing-type="calculator">
                    Kalkulator
                </button>
                @endif

            </div>

        </div>


        {{-- ================================
             WEBSITE PACKAGES
        ================================= --}}
        <div
            class="pricing-grid pricing-grid-website"
            data-pricing-content="website">

            @forelse($paket_website as $row)
            <article class="pricing-card @if($row->is_recommended) pricing-card-popular @endif">

                @if($row->is_recommended)
                <span class="pricing-popular">
                    Popular
                </span>
                @endif

                <div class="pricing-card-header">

                    <h3>
                        {{ $row->nama_paket }}
                    </h3>

                    <span class="pricing-start">
                        @if($row->tagline){{ $row->tagline }}@else Mulai dari @endif
                    </span>

                    <div class="pricing-price">
                        {{ $row->formatted_harga }}@if($row->periode)<span style="font-size:11px;font-weight:600;">/{{ $row->periode }}</span>@endif
                    </div>

                </div>


                <ul class="pricing-features">
                    @foreach($row->fitur_list as $fitur)
                    <li>
                        <span class="pricing-check">✓</span>
                        <span>{{ $fitur }}</span>
                    </li>
                    @endforeach
                </ul>


                <a href="#contact" class="pricing-button">
                    Pilih Paket
                </a>

            </article>
            @empty
            <p style="text-align:center;color:#667080;font-size:13px;grid-column:1/-1;">Belum ada paket website yang ditambahkan.</p>
            @endforelse

        </div>


        {{-- ================================
             APPLICATION PACKAGES
        ================================= --}}
        <div
            class="pricing-grid pricing-grid-application"
            data-pricing-content="application"
            style="display: none;">

            @forelse($paket_app as $row)
            <article class="pricing-card @if($row->is_recommended) pricing-card-popular @endif">

                @if($row->is_recommended)
                <span class="pricing-popular">
                    Popular
                </span>
                @endif

                <div class="pricing-card-header">

                    <h3>
                        {{ $row->nama_paket }}
                    </h3>

                    <span class="pricing-start">
                        @if($row->tagline){{ $row->tagline }}@else Mulai dari @endif
                    </span>

                    <div class="pricing-price">
                        {{ $row->formatted_harga }}@if($row->periode)<span style="font-size:11px;font-weight:600;">/{{ $row->periode }}</span>@endif
                    </div>

                </div>


                <ul class="pricing-features">
                    @foreach($row->fitur_list as $fitur)
                    <li>
                        <span class="pricing-check">✓</span>
                        <span>{{ $fitur }}</span>
                    </li>
                    @endforeach
                </ul>


                <a href="#contact" class="pricing-button">
                    Pilih Paket
                </a>

            </article>
            @empty
            <p style="text-align:center;color:#667080;font-size:13px;grid-column:1/-1;">Belum ada paket aplikasi yang ditambahkan.</p>
            @endforelse

        </div>


        {{-- ================================
             CUSTOM PROJECT CALCULATOR
        ================================= --}}
        @if($calculator_services->count())
        <div
            class="pricing-grid calc-wrapper"
            data-pricing-content="calculator"
            data-pricing-display="block"
            style="display: none;">

            <div class="calc-box">

                <div class="calc-form">

                    <div class="calc-group">
                        <label class="calc-label">1. Pilih Jenis Layanan</label>
                        <div class="calc-service-options">
                            @foreach($calculator_services as $index => $service)
                                <label class="calc-service-card">
                                    <input type="radio" name="calc_service" value="{{ $service->id }}"
                                        data-base="{{ $service->harga_dasar }}"
                                        data-perpage="{{ $service->harga_per_halaman }}"
                                        data-name="{{ $service->nama_layanan }}"
                                        {{ $index === 0 ? 'checked' : '' }}>
                                    <span class="calc-service-card-inner">
                                        <span class="calc-service-name">{{ $service->nama_layanan }}</span>
                                        <span class="calc-service-price">mulai Rp {{ number_format($service->harga_dasar,0,',','.') }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="calc-group">
                        <label class="calc-label" for="calc_pages">2. Jumlah Halaman / Fitur Inti</label>
                        <input type="number" id="calc_pages" class="calc-input-number" value="5" min="1" max="100">
                        <small class="calc-hint">Perkiraan jumlah halaman utama (Home, Tentang, Layanan, dsb).</small>
                    </div>

                    @if($calculator_features->count())
                    <div class="calc-group">
                        <label class="calc-label">3. Fitur Tambahan (opsional)</label>
                        <div class="calc-feature-options">
                            @foreach($calculator_features as $feature)
                                <label class="calc-feature-item">
                                    <input type="checkbox" name="calc_feature" value="{{ $feature->id }}"
                                        data-price="{{ $feature->harga_tambahan }}"
                                        data-name="{{ $feature->nama_fitur }}">
                                    <span class="calc-feature-inner">
                                        <span class="calc-feature-name">{{ $feature->nama_fitur }}</span>
                                        <span class="calc-feature-price">+Rp {{ number_format($feature->harga_tambahan,0,',','.') }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                <div class="calc-result">
                    <span class="calc-result-label">Estimasi Biaya Proyek Anda</span>
                    <div class="calc-result-range" id="calcResultRange">Rp 0 &ndash; Rp 0</div>
                    <p class="calc-result-note">*Estimasi awal, harga final ditentukan setelah konsultasi kebutuhan detail.</p>

                    <a href="#" id="calcConsultButton" target="_blank" rel="noopener" class="calc-consult-button">
                        <i class='bx bxl-whatsapp'></i>
                        Konsultasikan Project
                    </a>
                </div>

            </div>

        </div>
        @endif


        {{-- VIEW ALL --}}
        <div class="pricing-footer">

            <a href="#all-packages" class="pricing-all-button">
                Lihat Semua Paket

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
       PRICING SECTION
    ========================================= */

    .pricing-section {
        position: relative;

        width: 100%;

        padding: 55px 20px 60px;

        background: #ffffff;

        overflow: hidden;
    }

    .pricing-container {
        width: min(1450px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .pricing-header {
        text-align: center;

        margin-bottom: 9px;
    }

    .pricing-label {
        display: block;

        margin-bottom: 3px;

        color: #a91e2a;

        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: .9px;
    }

    .pricing-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3vw, 36px);
        line-height: 1.15;
        font-weight: 800;

        letter-spacing: -.8px;
    }

    .pricing-description {
        margin: 5px 0 9px;

        color: #596474;

        font-size: 13px;
        line-height: 1.4;
    }


    /* =========================================
       SWITCH
    ========================================= */

    .pricing-switch {
        display: inline-flex;

        padding: 0;

        overflow: hidden;

        background: #ffffff;

        border: 1px solid #d8dde3;
        border-radius: 14px;
    }

    .pricing-switch-btn {
        min-width: 130px;
        height: 34px;

        padding: 0 22px;

        color: #123567;
        background: #ffffff;

        border: 0;
        border-radius: 13px;

        font-family: inherit;
        font-size: 12px;
        font-weight: 700;

        cursor: pointer;

        transition:
            color .2s ease,
            background .2s ease;
    }

    .pricing-switch-btn.active {
        color: #ffffff;

        background: #123b70;
    }


    /* =========================================
       PRICING GRID
    ========================================= */

    .pricing-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 25px;

        margin-top: 10px;
    }


    /* =========================================
       CARD
    ========================================= */

    .pricing-card {
        position: relative;

        min-height: 305px;

        display: flex;
        flex-direction: column;

        padding: 14px 28px 14px;

        background: #ffffff;

        border: 1px solid #dfe3e8;
        border-radius: 10px;

        box-shadow:
            0 2px 8px rgba(18, 53, 103, .025);

        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .pricing-card:hover {
        transform: translateY(-4px);

        border-color: #b9c4d0;

        box-shadow:
            0 12px 28px rgba(18, 53, 103, .10);
    }

    .pricing-card-popular {
        border-color: #7d8998;

        box-shadow:
            0 2px 10px rgba(18, 53, 103, .08);
    }


    /* =========================================
       POPULAR BADGE
    ========================================= */

    .pricing-popular {
        position: absolute;

        top: 12px;
        right: 22px;

        padding: 5px 12px;

        color: #ffffff;
        background: #c3202d;

        border-radius: 14px;

        font-size: 10px;
        line-height: 1;
        font-weight: 700;
    }


    /* =========================================
       CARD HEADER
    ========================================= */

    .pricing-card-header {
        text-align: center;
    }

    .pricing-card-header h3 {
        margin: 0 0 4px;

        color: #142f54;

        font-size: 18px;
        line-height: 1.25;
        font-weight: 800;
    }

    .pricing-start {
        display: block;

        color: #343d48;

        font-size: 11px;
        line-height: 1.2;
    }

    .pricing-price {
        margin-top: 3px;

        color: #b21e29;

        font-size: 25px;
        line-height: 1.1;
        font-weight: 800;
    }


    /* =========================================
       FEATURES
    ========================================= */

    .pricing-features {
        display: flex;
        flex-direction: column;

        gap: 7px;

        margin: 15px 0 15px;
        padding: 0;

        list-style: none;
    }

    .pricing-features li {
        display: flex;
        align-items: center;

        gap: 10px;

        color: #303943;

        font-size: 12px;
        line-height: 1.3;
    }

    .pricing-check {
        flex: 0 0 15px;

        color: #123b70;

        font-size: 18px;
        line-height: 12px;
        font-weight: 700;
    }


    /* =========================================
       BUTTON
    ========================================= */

    .pricing-button {
        width: 100%;
        min-height: 39px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-top: auto;

        color: #ffffff;
        background: #123b70;

        border-radius: 7px;

        font-size: 12px;
        line-height: 1;
        font-weight: 700;

        text-decoration: none;

        transition:
            background .2s ease,
            transform .2s ease;
    }

    .pricing-button:hover {
        background: #092d59;

        transform: translateY(-1px);
    }


    /* =========================================
       FOOTER
    ========================================= */

    .pricing-footer {
        display: flex;
        justify-content: center;

        margin-top: 8px;
    }

    .pricing-all-button {
        min-height: 38px;

        padding: 0 25px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        color: #123567;
        background: #ffffff;

        border: 1px solid #d6dce2;
        border-radius: 8px;

        font-size: 11px;
        font-weight: 700;

        text-decoration: none;

        transition:
            color .2s ease,
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }

    .pricing-all-button svg {
        width: 15px;
        height: 15px;

        stroke: currentColor;
        stroke-width: 1.8;

        transition: transform .2s ease;
    }

    .pricing-all-button:hover {
        color: #ffffff;
        background: #123b70;
        border-color: #123b70;

        transform: translateY(-2px);
    }

    .pricing-all-button:hover svg {
        transform: translateX(4px);
    }


    /* =========================================
       CALCULATOR
    ========================================= */

    .calc-wrapper {
        display: block;
    }

    .calc-box {
        display: grid;

        grid-template-columns: 1.4fr 1fr;

        gap: 26px;

        padding: 26px;

        background: #f6f8fb;

        border: 1px solid #e2e6eb;
        border-radius: 14px;
    }

    .calc-group {
        margin-bottom: 22px;
    }

    .calc-group:last-child {
        margin-bottom: 0;
    }

    .calc-label {
        display: block;

        margin-bottom: 10px;

        color: #142f54;

        font-size: 13px;
        font-weight: 700;
    }

    .calc-service-options,
    .calc-feature-options {
        display: flex;
        flex-direction: column;

        gap: 8px;
    }

    .calc-service-card,
    .calc-feature-item {
        display: block;

        cursor: pointer;
    }

    .calc-service-card input,
    .calc-feature-item input {
        position: absolute;

        opacity: 0;

        pointer-events: none;
    }

    .calc-service-card-inner,
    .calc-feature-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 12px;

        padding: 11px 14px;

        background: #ffffff;

        border: 1.5px solid #dde2e8;
        border-radius: 9px;

        font-size: 12.5px;

        transition: border-color .2s ease, background .2s ease;
    }

    .calc-service-card input:checked + .calc-service-card-inner,
    .calc-feature-item input:checked + .calc-feature-inner {
        background: #eef2f8;
        border-color: #123b70;
    }

    .calc-service-name,
    .calc-feature-name {
        color: #22283a;
        font-weight: 700;
    }

    .calc-service-price,
    .calc-feature-price {
        flex: 0 0 auto;

        color: #123b70;
        font-weight: 700;

        white-space: nowrap;
    }

    .calc-input-number {
        width: 120px;

        padding: 9px 12px;

        color: #22283a;
        background: #ffffff;

        border: 1.5px solid #dde2e8;
        border-radius: 9px;

        font-family: inherit;
        font-size: 13px;
    }

    .calc-input-number:focus {
        outline: none;
        border-color: #123b70;
    }

    .calc-hint {
        display: block;

        margin-top: 6px;

        color: #7c8798;

        font-size: 10.5px;
    }

    .calc-result {
        display: flex;
        flex-direction: column;

        justify-content: center;

        padding: 26px 22px;

        text-align: center;

        color: #ffffff;
        background: #123567;

        border-radius: 12px;
    }

    .calc-result-label {
        color: rgba(255, 255, 255, .72);

        font-size: 11.5px;
        font-weight: 700;

        letter-spacing: .3px;

        text-transform: uppercase;
    }

    .calc-result-range {
        margin: 10px 0 8px;

        color: #ffffff;

        font-size: 22px;
        font-weight: 800;

        line-height: 1.2;
    }

    .calc-result-note {
        margin: 0 0 20px;

        color: rgba(255, 255, 255, .64);

        font-size: 10.5px;
        line-height: 1.5;
    }

    .calc-consult-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        min-height: 42px;

        padding: 0 18px;

        color: #ffffff;
        background: #25b358;

        border-radius: 8px;

        font-size: 12.5px;
        font-weight: 700;

        text-decoration: none;

        transition: background .2s ease, transform .2s ease;
    }

    .calc-consult-button:hover {
        background: #1e9648;

        transform: translateY(-1px);
    }


    /* =========================================
       TABLET
    ========================================= */

    @media (max-width: 1050px) {

        .pricing-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            max-width: 800px;

            margin-left: auto;
            margin-right: auto;
        }

        .calc-wrapper {
            max-width: none;
        }

        .calc-box {
            grid-template-columns: 1fr;
        }
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 600px) {

        .pricing-section {
            padding: 45px 15px 50px;
        }

        .pricing-title {
            font-size: 27px;
        }

        .pricing-description {
            font-size: 12px;
        }

        .pricing-switch-btn {
            min-width: 110px;
        }

        .pricing-grid {
            grid-template-columns: 1fr;

            max-width: 400px;
        }

        .pricing-card {
            min-height: 300px;

            padding-left: 24px;
            padding-right: 24px;
        }
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const switchButtons = document.querySelectorAll(
            '.pricing-switch-btn'
        );

        const pricingContents = document.querySelectorAll(
            '[data-pricing-content]'
        );


        switchButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                const type = this.dataset.pricingType;


                /* Update active button */
                switchButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                this.classList.add('active');


                /* Toggle pricing grids */
                pricingContents.forEach(function (content) {

                    if (
                        content.dataset.pricingContent === type
                    ) {

                        // FIXED: sebelumnya selalu 'grid', padahal panel
                        // kalkulator butuh 'block' (bukan grid card).
                        content.style.display = content.dataset.pricingDisplay || 'grid';

                        content.animate(
                            [
                                {
                                    opacity: 0,
                                    transform: 'translateY(8px)'
                                },
                                {
                                    opacity: 1,
                                    transform: 'translateY(0)'
                                }
                            ],
                            {
                                duration: 250,
                                easing: 'ease-out'
                            }
                        );

                    } else {

                        content.style.display = 'none';

                    }

                });

            });

        });


        // FIXED: link "Kalkulator" di navbar (class nav-open-calculator-tab)
        // mengarah ke #pricing?tab=calculator, tapi sebelumnya tidak ada JS
        // yang membaca parameter itu dan membuka tab kalkulator secara
        // otomatis. Dicek lewat query string supaya link "Harga" biasa
        // (yang cuma pakai #pricing) tetap membuka tab Website seperti biasa.
        var calcTabButton = document.querySelector('.pricing-switch-btn[data-pricing-type="calculator"]');
        if (calcTabButton) {
            var params = new URLSearchParams(window.location.search);
            if (params.get('tab') === 'calculator') {
                calcTabButton.click();
            }
        }

    });
</script>


<script>
    // ========================================
    // PROJECT COST CALCULATOR
    // ========================================
    document.addEventListener('DOMContentLoaded', function () {

        const serviceInputs = document.querySelectorAll('input[name="calc_service"]');
        const featureInputs = document.querySelectorAll('input[name="calc_feature"]');
        const pagesInput = document.getElementById('calc_pages');
        const resultRange = document.getElementById('calcResultRange');
        const consultButton = document.getElementById('calcConsultButton');

        if (!serviceInputs.length || !pagesInput || !resultRange) return;

        const CALC_WA_NUMBER = '6285151811055';
        const RANGE_LOW = 0.9;
        const RANGE_HIGH = 1.25;

        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function getSelectedService() {
            let selected = null;
            serviceInputs.forEach(function (input) {
                if (input.checked) selected = input;
            });
            return selected;
        }

        function getSelectedFeatures() {
            const selected = [];
            featureInputs.forEach(function (input) {
                if (input.checked) {
                    selected.push({
                        name: input.dataset.name,
                        price: parseFloat(input.dataset.price) || 0
                    });
                }
            });
            return selected;
        }

        function calculateAndRender() {
            const service = getSelectedService();
            if (!service) return;

            const base = parseFloat(service.dataset.base) || 0;
            const perPage = parseFloat(service.dataset.perpage) || 0;
            const pages = Math.max(1, parseInt(pagesInput.value, 10) || 1);
            const features = getSelectedFeatures();
            const featureTotal = features.reduce(function (sum, f) { return sum + f.price; }, 0);

            const subtotal = base + (perPage * pages) + featureTotal;
            const low = subtotal * RANGE_LOW;
            const high = subtotal * RANGE_HIGH;

            resultRange.textContent = formatRupiah(low) + ' \u2013 ' + formatRupiah(high);

            let message = 'Halo Empatra DigiTech, saya sudah coba kalkulator estimasi biaya di website dengan rincian:\n\n';
            message += '\uD83D\uDCCC Layanan: ' + service.dataset.name + '\n';
            message += '\uD83D\uDCC4 Jumlah Halaman: ' + pages + '\n';
            if (features.length) {
                message += '\u2795 Fitur Tambahan: ' + features.map(function (f) { return f.name; }).join(', ') + '\n';
            }
            message += '\uD83D\uDCB0 Estimasi: ' + formatRupiah(low) + ' - ' + formatRupiah(high) + '\n\n';
            message += 'Boleh dibantu konsultasikan lebih lanjut untuk project ini?';

            if (consultButton) {
                consultButton.href = 'https://wa.me/' + CALC_WA_NUMBER + '?text=' + encodeURIComponent(message);
            }
        }

        serviceInputs.forEach(function (input) {
            input.addEventListener('change', calculateAndRender);
        });
        featureInputs.forEach(function (input) {
            input.addEventListener('change', calculateAndRender);
        });
        pagesInput.addEventListener('input', calculateAndRender);

        // Initial calculation on load
        calculateAndRender();
    });
</script>