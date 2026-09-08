<footer class="site-footer">

    <div class="footer-container">

        {{-- =========================================
             MAIN FOOTER
             FIX: kembali ke 4 kolom (Company, Navigasi, Layanan, Kontak)
             seperti desain lama. Alamat + Peta digabung ke dalam
             kolom "Hubungi Kami" alih-alih jadi kolom ke-5 sendiri,
             karena grid-template-columns hanya mendefinisikan 4 track.
             Dulu ada 5 div tapi cuma 4 kolom -> item ke-5 (Kontak)
             kelempar ke baris baru di bawah. Itu penyebab footer
             jadi panjang & berantakan di versi backend.
        ========================================== --}}
        <div class="footer-main">

            {{-- =========================================
                 COMPANY
            ========================================== --}}
            <div class="footer-company">

                <a href="{{ route('home.home.index') }}" class="footer-logo">

                    @if(!empty($table_pengaturan?->website_logo))

                        <img
                            src="{{ asset('storage/' . $table_pengaturan->website_logo) }}"
                            alt="{{ $table_pengaturan->website_name ?? 'Empatra DigiTech' }}"
                        >

                    @else

                        <img
                            src="{{ asset('assets/img/favicon.png') }}"
                            alt="{{ $table_pengaturan->website_name ?? 'Empatra DigiTech' }}"
                        >

                    @endif

                    {{-- FIX: nama website disatukan sebaris dengan logo
                         (dulu jadi blok terpisah di bawah logo, bikin
                         kolom company makan tempat vertikal lebih banyak) --}}
                    @if(!empty($table_pengaturan?->website_name))
                        <span class="footer-logo-text">
                            {{ $table_pengaturan->website_name }}
                        </span>
                    @endif

                </a>


                {{-- MOTTO --}}
                @if(!empty($table_pengaturan?->website_motto))
                    <p class="footer-description">
                        {{ $table_pengaturan->website_motto }}
                    </p>
                @endif


                {{-- SOCIAL MEDIA --}}
                @php

                    $footerIconMap = [

                        'instagram' =>
                            '<rect x="3" y="3" width="18" height="18" rx="5"/>
                             <circle cx="12" cy="12" r="4"/>
                             <circle cx="17.5" cy="6.5" r="1"/>',

                        'facebook' =>
                            '<path d="M14 8h3V4h-3c-3.3 0-5 2-5 5v3H6v4h3v4h4v-4h3l1-4h-4V9c0-.7.3-1 1-1Z"/>',

                        'linkedin' =>
                            '<rect x="4" y="4" width="16" height="16" rx="2"/>
                             <path d="M8 10v6"/>
                             <path d="M8 7.5v.01"/>
                             <path d="M12 16v-3.5a2.5 2.5 0 0 1 5 0V16"/>
                             <path d="M12 10v6"/>',

                        'whatsapp' =>
                            '<path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5Z"/>
                             <path d="M8.5 8.5c.3-.4.7-.4 1-.1l1.2 1.2c.3.3.3.6.1.9l-.5.7c.7 1.2 1.6 2.1 2.8 2.8l.7-.5c.3-.2.6-.2.9.1l1.2 1.2c.3.3.3.7-.1 1-1 .8-2.4.6-4.1-.5-1.6-1-2.9-2.3-3.9-3.9-1.1-1.7-1.3-3.1-.5-4.1Z"/>',

                        'tiktok' =>
                            '<path d="M14 3v10.5a2.8 2.8 0 1 1-2-2.68"/>
                             <path d="M14 3c.3 2.4 1.8 4 4.5 4.2"/>',

                        'youtube' =>
                            '<rect x="3" y="6" width="18" height="12" rx="3"/>
                             <path d="m10.5 9.5 5 2.5-5 2.5Z"/>',

                        'twitter' =>
                            '<path d="M4 4l7.5 9.6L4.3 20H7l5.7-5.4L17 20h3l-8-10.2L19 4h-2.7l-5 4.8L7 4Z"/>',

                        'x.com' =>
                            '<path d="M4 4l7.5 9.6L4.3 20H7l5.7-5.4L17 20h3l-8-10.2L19 4h-2.7l-5 4.8L7 4Z"/>',
                    ];

                    $footerIconDefault =
                        '<circle cx="12" cy="12" r="9"/>
                         <path d="M9 12h6"/>
                         <path d="M12 9v6"/>';

                    $footerLinks = collect($table_tautan ?? [])
                        ->filter(fn ($tautan) => !empty($tautan->url));

                @endphp


                @if($footerLinks->count())

                    <div class="footer-socials">

                        @foreach($footerLinks as $tautan)

                            @php

                                $tautanTitle = strtolower(
                                    $tautan->title ?? ''
                                );

                                $tautanIcon = $footerIconDefault;

                                foreach ($footerIconMap as $keyword => $iconMarkup) {

                                    if (str_contains($tautanTitle, $keyword)) {

                                        $tautanIcon = $iconMarkup;

                                        break;
                                    }
                                }

                            @endphp


                            <a
                                href="{{ $tautan->url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="{{ $tautan->title }}"
                                class="footer-social"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    {!! $tautanIcon !!}
                                </svg>

                            </a>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- =========================================
                 NAVIGATION
            ========================================== --}}
            <div class="footer-column">

                <h3>
                    Navigasi
                </h3>

                @php
                    $footerMenus = collect($table_menu ?? [])
                        ->whereNull('parent')
                        ->sortBy('created_at');

                    $reservedTitles = [
                        'home',
                        'beranda',
                    ];

                    $footerMenusFiltered = $footerMenus->reject(
                        fn ($menu) => in_array(strtolower(trim($menu->title)), $reservedTitles)
                    );

                  
                    $navTotal = 1 + $footerMenusFiltered->count();
                @endphp

                <ul class="{{ $navTotal > 6 ? 'two-col' : '' }}">

                    <li>
                        <a href="{{ route('home.home.index') }}">
                            Home
                        </a>
                    </li>

                    @foreach($footerMenusFiltered as $menu)

                        <li>
                            <a href="{{ '/' . strtolower($menu->title) . '/show' }}">
                                {{ $menu->title }}
                            </a>
                        </li>

                    @endforeach

                </ul>

            </div>


            {{-- =========================================
                 SERVICES
            ========================================== --}}
            <div class="footer-column">

                <h3>
                    Layanan Kami
                </h3>

                @php
                    $layananTotal = 1 + (isset($table_layanan) ? $table_layanan->take(5)->count() : 0);
                @endphp

                <ul class="{{ $layananTotal > 6 ? 'two-col' : '' }}">

                    <li>
                        <a href="{{ route('home.home.index') }}#layanan">
                            Semua Layanan
                        </a>
                    </li>

                    @if(isset($table_layanan))

                        @foreach($table_layanan->take(5) as $layanan)

                            <li>
                                <a href="{{ route('home.home.index') }}#layanan">
                                    {{ $layanan->title }}
                                </a>
                            </li>

                        @endforeach

                    @endif

                </ul>

            </div>


            {{-- =========================================
                 CONTACT (+ ALAMAT & PETA digabung di sini)
            ========================================== --}}
            <div class="footer-column footer-contact">

                <h3>
                    Hubungi Kami
                </h3>

                <div class="footer-contact-list">


                    {{-- EMAIL --}}
                    @if(!empty($table_pengaturan?->website_email))

                        <a
                            href="mailto:{{ $table_pengaturan->website_email }}"
                            class="footer-contact-item"
                        >

                            <span class="footer-contact-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    />

                                    <path d="m4 7 8 6 8-6"/>

                                </svg>

                            </span>


                            <span>
                                {{ $table_pengaturan->website_email }}
                            </span>

                        </a>

                    @endif


                    {{-- PHONE --}}
                    @if(!empty($table_pengaturan?->website_phone))

                        @php

                            $phone = preg_replace(
                                '/[^0-9]/',
                                '',
                                $table_pengaturan->website_phone
                            );

                            if(str_starts_with($phone, '0')) {
                                $phone = '62' . substr($phone, 1);
                            }

                        @endphp


                        <a
                            href="tel:+{{ $phone }}"
                            class="footer-contact-item"
                        >

                            <span class="footer-contact-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path d="M7 3h3l1.5 4-2 1.5a15 15 0 0 0 6 6L17 12l4 1.5v3c0 1.4-1.1 2.5-2.5 2.5C10.5 19 5 13.5 5 5.5 5 4.1 6.1 3 7 3Z"/>
                                </svg>

                            </span>


                            <span>
                                {{ $table_pengaturan->website_phone }}
                            </span>

                        </a>

                    @endif


                    {{-- ADDRESS --}}
                    @if(!empty($table_pengaturan?->website_address))

                        <div class="footer-contact-item">

                            <span class="footer-contact-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/>

                                    <circle
                                        cx="12"
                                        cy="9"
                                        r="2.5"
                                    />

                                </svg>

                            </span>


                            <span>
                                {{ $table_pengaturan->website_address }}
                            </span>

                        </div>

                    @endif

                </div>


                {{-- =========================================
                     PETA (MINI MAP)
                     FIX: tautan Google Maps yang benar untuk iframe
                     HARUS dari Google Maps -> Share -> "Embed a map"
                     (biasanya mengandung /maps/embed atau output=embed).
                     Kalau field website_maps diisi link share biasa,
                     iframe akan menampilkan error 404 seperti di
                     screenshot kamu. Karena itu di-cek dulu; kalau
                     bukan link embed yang valid, ditampilkan tombol
                     "Lihat di Google Maps" saja alih-alih iframe rusak.
                ========================================== --}}
                @php
                    $mapsUrl = $table_pengaturan->website_maps ?? null;

                    $mapsEmbeddable = !empty($mapsUrl) && (
                        str_contains($mapsUrl, '/maps/embed')
                        || str_contains($mapsUrl, 'output=embed')
                    );
                @endphp

                @if($mapsEmbeddable)

                    <div class="footer-map-wrapper">

                        <iframe
                            id="maps_mini"
                            src="{{ $mapsUrl }}"
                            frameborder="0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi {{ $table_pengaturan->website_name ?? 'Kantor' }}"
                        ></iframe>

                    </div>

                @elseif(!empty($mapsUrl))

                    <a
                        href="{{ $mapsUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="footer-map-fallback"
                    >

                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/>
                            <circle cx="12" cy="9" r="2.5"/>
                        </svg>

                        Lihat Lokasi di Google Maps

                    </a>

                @endif

            </div>

        </div>


        {{-- =========================================
             CTA
        ========================================== --}}

        @php

           
            $whatsappTautan = collect($table_tautan ?? [])
                ->first(function ($tautan) {

                    return str_contains(
                        strtolower($tautan->title ?? ''),
                        'whatsapp'
                    ) && !empty($tautan->url);

                });

            $whatsappUrl = $whatsappTautan->url ?? null;

            
            if (empty($whatsappUrl) && !empty($table_pengaturan?->website_phone)) {

                $waPhone = preg_replace('/[^0-9]/', '', $table_pengaturan->website_phone);

                if (str_starts_with($waPhone, '0')) {
                    $waPhone = '62' . substr($waPhone, 1);
                }

                $waMessage = 'Halo ' . ($table_pengaturan->website_name ?? '') . ', saya ingin konsultasi gratis untuk kebutuhan digital saya.';

                $whatsappUrl = 'https://wa.me/' . $waPhone . '?text=' . urlencode($waMessage);
            }

        @endphp


        <div class="footer-cta">

            <div class="footer-cta-content">

                <span>
                    Punya project atau ide?
                </span>

                <h3>
                    Mari wujudkan bersama.
                </h3>

            </div>


            @if($whatsappUrl)

                <a
                    href="{{ $whatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="footer-cta-button"
                >

                    Konsultasi Gratis

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>

                    </svg>

                </a>

            @endif

        </div>


        {{-- =========================================
             BOTTOM FOOTER
        ========================================== --}}

        <div class="footer-bottom">

            <p>

                © {{ date('Y') }}

                {{ $table_pengaturan->website_name ?? 'Empatra Digitech' }}.

                All rights reserved.

            </p>


            <div class="footer-legal">

                <a href="#">
                    Privacy Policy
                </a>

                <span></span>

                <a href="#">
                    Terms & Conditions
                </a>

            </div>

        </div>

    </div>

</footer>


<style>
    /* =========================================
       FOOTER
    ========================================= */

    .site-footer {
        position: relative;

        width: 100%;

        color: #ffffff;

        background: #082f5d;

        overflow: hidden;
    }

    .site-footer::before {
        content: "";

        position: absolute;

        width: 420px;
        height: 420px;

        top: -250px;
        right: -150px;

        border-radius: 50%;

        background: rgba(255, 255, 255, .025);

        pointer-events: none;
    }

    .site-footer::after {
        content: "";

        position: absolute;

        width: 300px;
        height: 300px;

        left: -180px;
        bottom: -200px;

        border-radius: 50%;

        background: rgba(196, 30, 43, .045);

        pointer-events: none;
    }

    .footer-container {
        position: relative;

        z-index: 2;

        width: min(1180px, 100%);

        margin: 0 auto;

        padding: 55px 20px 0;
        box-sizing: border-box;
    }

    .site-footer * {
        box-sizing: border-box;
        min-width: 0;
    }


    /* =========================================
       MAIN FOOTER
       FIX: tetap 4 kolom, sama seperti desain lama.
    ========================================= */

    .footer-main {
        display: grid;

        grid-template-columns:
            2fr
            1fr
            1fr
            1.45fr;

        gap: 55px;

        padding-bottom: 38px;
    }


    /* =========================================
       COMPANY
    ========================================= */

    .footer-logo {
        display: inline-flex;
        align-items: center;

        gap: 10px;

        margin-bottom: 14px;

        color: #ffffff;

        font-size: 20px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: -.5px;

        text-decoration: none;
    }

    .footer-logo img {
        display: block;

        height: 36px;
        width: auto;
        max-width: 140px;

        object-fit: contain;
    }

    .footer-logo-text {
        color: #ffffff;
    }

    .footer-description {
        max-width: 310px;

        margin: 0;

        color: rgba(255, 255, 255, .68);

        font-size: 11px;
        line-height: 1.75;
    }


    /* =========================================
       SOCIAL
    ========================================= */

    .footer-socials {
        display: flex;
        flex-wrap: wrap;

        align-items: center;

        gap: 8px;

        margin-top: 20px;
    }

    .footer-social {
        width: 32px;
        height: 32px;
        flex: 0 0 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: rgba(255, 255, 255, .75);

        background: rgba(255, 255, 255, .07);

        border: 1px solid rgba(255, 255, 255, .12);

        border-radius: 6px;

        transition:
            color .2s ease,
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }

    .footer-social svg {
        width: 16px;
        height: 16px;

        stroke: currentColor;

        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .footer-social:hover {
        color: #ffffff;

        background: #c3202d;

        border-color: #c3202d;

        transform: translateY(-2px);
    }


    /* =========================================
       FOOTER COLUMNS
    ========================================= */

    .footer-column {
        min-width: 0;
    }

    .footer-column h3 {
        margin: 2px 0 17px;

        color: #ffffff;

        font-size: 13px;
        line-height: 1.3;
        font-weight: 800;
    }

    .footer-column ul {
        display: flex;
        flex-direction: column;

        gap: 10px;

        margin: 0;
        padding: 0;

        list-style: none;
    }

    /* FIX: kalau item menu/layanan dari backend banyak, pecah jadi 2
       kolom biar footer tidak memanjang ke bawah */
    .footer-column ul.two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;

        gap: 10px 18px;
    }

    .footer-column li {
        margin: 0;
        padding: 0;
    }

    .footer-column li a {
        display: inline-block;

        color: rgba(255, 255, 255, .63);

        font-size: 11px;
        line-height: 1.4;

        text-decoration: none;

        transition:
            color .2s ease,
            transform .2s ease;
    }

    .footer-column li a:hover {
        color: #ffffff;

        transform: translateX(4px);
    }


    /* =========================================
       CONTACT
    ========================================= */

    .footer-contact-list {
        display: flex;
        flex-direction: column;

        gap: 13px;

        margin-bottom: 16px;
    }

    .footer-contact-item {
        display: flex;
        align-items: flex-start;

        gap: 9px;

        color: rgba(255, 255, 255, .67);

        font-size: 10px;
        line-height: 1.5;

        text-decoration: none;

        overflow-wrap: break-word;
        word-break: break-word;
    }

    a.footer-contact-item {
        transition: color .2s ease;
    }

    a.footer-contact-item:hover {
        color: #ffffff;
    }

    .footer-contact-icon {
        width: 27px;
        height: 27px;

        flex: 0 0 27px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, .07);

        border-radius: 6px;
    }

    .footer-contact-icon svg {
        width: 14px;
        height: 14px;

        stroke: currentColor;

        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* =========================================
       MAP (mini, di dalam kolom kontak)
    ========================================= */

    .footer-map-wrapper {
        position: relative;

        width: 100%;
        aspect-ratio: 16 / 10;

        overflow: hidden;

        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, .12);

        background: rgba(255, 255, 255, .04);
    }

    .footer-map-wrapper iframe {
        position: absolute;
        inset: 0;

        width: 100%;
        height: 100%;

        border: 0;

        filter: grayscale(.15) contrast(1.05);
    }

    /* FIX: tombol fallback kalau URL maps bukan link embed yang valid,
       supaya tidak muncul kotak error 404 */
    .footer-map-fallback {
        display: flex;
        align-items: center;

        gap: 8px;

        padding: 10px 12px;

        color: rgba(255, 255, 255, .8);

        font-size: 10px;
        font-weight: 600;

        text-decoration: none;

        background: rgba(255, 255, 255, .06);

        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 8px;

        transition:
            color .2s ease,
            background .2s ease,
            border-color .2s ease;
    }

    .footer-map-fallback svg {
        width: 15px;
        height: 15px;
        flex: 0 0 15px;

        stroke: currentColor;

        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .footer-map-fallback:hover {
        color: #ffffff;

        background: #c3202d;

        border-color: #c3202d;
    }


    /* =========================================
       CTA
    ========================================= */

    .footer-cta {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 25px;

        padding: 19px 22px;

        background: rgba(255, 255, 255, .055);

        border: 1px solid rgba(255, 255, 255, .11);

        border-radius: 8px;
    }

    .footer-cta-content {
        display: flex;
        flex-direction: column;

        gap: 3px;
    }

    .footer-cta-content span {
        color: rgba(255, 255, 255, .62);

        font-size: 10px;
        line-height: 1.3;
    }

    .footer-cta-content h3 {
        margin: 0;

        color: #ffffff;

        font-size: 16px;
        line-height: 1.3;
        font-weight: 800;
    }

    .footer-cta-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        gap: 8px;

        min-height: 38px;

        padding: 0 18px;

        color: #ffffff;

        background: #c3202d;

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

    .footer-cta-button svg {
        width: 14px;
        height: 14px;

        stroke: currentColor;

        stroke-width: 1.8;

        stroke-linecap: round;
        stroke-linejoin: round;

        transition: transform .2s ease;
    }

    .footer-cta-button:hover {
        background: #a91e2a;

        transform: translateY(-2px);

        box-shadow:
            0 6px 14px rgba(0, 0, 0, .18);
    }

    .footer-cta-button:hover svg {
        transform: translateX(3px);
    }


    /* =========================================
       BOTTOM
    ========================================= */

    .footer-bottom {
        min-height: 58px;

        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;

        gap: 12px 20px;

        margin-top: 35px;

        border-top: 1px solid rgba(255, 255, 255, .10);
    }

    .footer-bottom p {
        margin: 0;

        color: rgba(255, 255, 255, .48);

        font-size: 9px;
        line-height: 1.4;
    }

    .footer-legal {
        display: flex;
        align-items: center;

        gap: 12px;
    }

    .footer-legal a {
        color: rgba(255, 255, 255, .48);

        font-size: 9px;
        line-height: 1.4;

        text-decoration: none;

        transition: color .2s ease;
    }

    .footer-legal a:hover {
        color: #ffffff;
    }

    .footer-legal span {
        width: 3px;
        height: 3px;
        flex: 0 0 3px;

        background: rgba(255, 255, 255, .35);

        border-radius: 50%;
    }


    /* =========================================
       TABLET
    ========================================= */

    @media (max-width: 900px) {

        .footer-main {
            grid-template-columns:
                1.5fr
                1fr
                1fr;

            gap: 40px;
        }

        .footer-contact {
            grid-column: span 3;
        }
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 600px) {

        .footer-container {
            padding: 45px 18px 0;
        }

        .footer-main {
            grid-template-columns: 1fr 1fr;

            gap: 35px 25px;

            padding-bottom: 30px;
        }

        .footer-company {
            grid-column: span 2;
        }

        .footer-contact {
            grid-column: span 2;
        }

        .footer-description {
            max-width: 100%;
        }

        .footer-column ul.two-col {
            grid-template-columns: 1fr 1fr;
        }

        .footer-map-wrapper {
            aspect-ratio: 16 / 9;
        }

        .footer-cta {
            flex-direction: column;

            align-items: flex-start;

            gap: 15px;
        }

        .footer-cta-button {
            width: 100%;
        }

        .footer-bottom {
            flex-direction: column;

            justify-content: center;

            gap: 8px;

            padding: 18px 0;

            text-align: center;
        }
    }


    /* =========================================
       SMALL MOBILE
    ========================================= */

    @media (max-width: 400px) {

        .footer-main {
            grid-template-columns: 1fr;
        }

        .footer-company,
        .footer-contact {
            grid-column: auto;
        }

        .footer-column ul.two-col {
            grid-template-columns: 1fr;
        }

        .footer-legal {
            flex-wrap: wrap;

            justify-content: center;
        }
    }
</style>