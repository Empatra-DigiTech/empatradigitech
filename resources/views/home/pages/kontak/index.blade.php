@extends('home.layouts.master')
@section("title","Kontak | EMPATRA DIGITECH")
@section('meta_description', 'Hubungi Empatra Digitech untuk konsultasi gratis kebutuhan website, aplikasi, dan sistem digital bisnis Anda.')

@section('content')

<section class="kontak-section">

    <div class="kontak-container">

        {{-- =========================================
             HEADER
        ========================================== --}}
        <div class="kontak-header">

            <span class="kontak-label">
                HUBUNGI KAMI
            </span>

            <h1 class="kontak-title">
                Mari Wujudkan Project Anda
            </h1>

            <p class="kontak-subtitle">
                Ceritakan kebutuhan digital bisnis Anda, tim kami akan
                merespon secepatnya. Konsultasi awal 100% gratis.
            </p>

        </div>


        <div class="kontak-grid">

            {{-- =========================================
                 CONTACT INFO
            ========================================== --}}
            <div class="kontak-info">

                <h2 class="kontak-info-title">
                    Informasi Kontak
                </h2>

                <p class="kontak-info-desc">
                    Silakan hubungi kami langsung, atau isi kebutuhan Anda di
                    samping agar pesan WhatsApp otomatis tersusun rapi.
                </p>

                <div class="kontak-info-list">

                    @if(!empty($table_pengaturan->website_email))
                    <a href="mailto:{{ $table_pengaturan->website_email }}" class="kontak-info-item">
                        <span class="kontak-info-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="m4 7 8 6 8-6"/>
                            </svg>
                        </span>
                        <span>
                            <strong>Email</strong>
                            <small>{{ $table_pengaturan->website_email }}</small>
                        </span>
                    </a>
                    @endif

                    @if(!empty($table_pengaturan->website_phone))
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $table_pengaturan->website_phone)) }}" target="_blank" rel="noopener" class="kontak-info-item">
                        <span class="kontak-info-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M7 3h3l1.5 4-2 1.5a15 15 0 0 0 6 6L17 12l4 1.5v3c0 1.4-1.1 2.5-2.5 2.5C10.5 19 5 13.5 5 5.5 5 4.1 6.1 3 7 3Z"/>
                            </svg>
                        </span>
                        <span>
                            <strong>Telepon / WhatsApp</strong>
                            <small>{{ $table_pengaturan->website_phone }}</small>
                        </span>
                    </a>
                    @endif

                    @if(!empty($table_pengaturan->website_address))
                    <div class="kontak-info-item">
                        <span class="kontak-info-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.5"/>
                            </svg>
                        </span>
                        <span>
                            <strong>Alamat</strong>
                            <small>{{ $table_pengaturan->website_address }}</small>
                        </span>
                    </div>
                    @endif

                </div>

            </div>


            {{-- =========================================
                 WHATSAPP LEAD SYSTEM
                 (menggantikan form konvensional — pengunjung
                 memilih layanan, budget, dan kebutuhan, lalu
                 sistem merangkai pesan WhatsApp otomatis)
            ========================================== --}}
            <div class="kontak-form-wrapper">

                <div class="kontak-wa-header">
                    <span class="kontak-wa-header-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5Z"/>
                            <path d="M8.5 8.5c.3-.4.7-.4 1-.1l1.2 1.2c.3.3.3.6.1.9l-.5.7c.7 1.2 1.6 2.1 2.8 2.8l.7-.5c.3-.2.6-.2.9.1l1.2 1.2c.3.3.3.7-.1 1-1 .8-2.4.6-4.1-.5-1.6-1-2.9-2.3-3.9-3.9-1.1-1.7-1.3-3.1-.5-4.1Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="kontak-info-title" style="color:#123567;">Konsultasi via WhatsApp</h2>
                        <p class="kontak-wa-header-desc">Isi kebutuhan Anda, pesan otomatis tersusun rapi — tinggal kirim</p>
                    </div>
                </div>

                <div class="kontak-form-row">
                    <div class="kontak-form-group">
                        <label for="leadName">Nama Anda</label>
                        <input type="text" id="leadName" class="kontak-input" placeholder="cth. Budi Santoso">
                    </div>
                    <div class="kontak-form-group">
                        <label for="leadLayanan">Layanan yang Diminati</label>
                        <select id="leadLayanan" class="kontak-input">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($table_layanan as $layananItem)
                                <option value="{{ $layananItem->title }}">{{ $layananItem->title }}</option>
                            @endforeach
                            <option value="Lainnya / Belum yakin">Lainnya / Belum yakin</option>
                        </select>
                    </div>
                </div>

                <div class="kontak-form-group">
                    <label for="leadBudget">Estimasi Anggaran</label>
                    <select id="leadBudget" class="kontak-input">
                        <option value="">-- Pilih Estimasi Anggaran (opsional) --</option>
                        <option value="< Rp 5 juta">&lt; Rp 5 juta</option>
                        <option value="Rp 5 - 15 juta">Rp 5 - 15 juta</option>
                        <option value="Rp 15 - 50 juta">Rp 15 - 50 juta</option>
                        <option value="> Rp 50 juta">&gt; Rp 50 juta</option>
                        <option value="Belum tahu, perlu diskusi">Belum tahu, perlu diskusi</option>
                    </select>
                </div>

                <div class="kontak-form-group">
                    <label for="leadDesc">Deskripsi Kebutuhan</label>
                    <textarea id="leadDesc" rows="4" class="kontak-input kontak-textarea"
                        placeholder="Ceritakan singkat kebutuhan proyek Anda..."></textarea>
                </div>

                <div class="kontak-wa-preview">
                    <div class="kontak-wa-preview-label">Preview Pesan WhatsApp</div>
                    <div class="kontak-wa-preview-bubble" id="waPreviewText"></div>
                </div>

                <button type="button" id="waSendLeadBtn" class="kontak-submit kontak-submit-wa" disabled>
                    Kirim ke WhatsApp
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </button>
                <small class="kontak-hint">Lengkapi Nama, Layanan, dan Deskripsi — Anda akan diarahkan ke WhatsApp dengan pesan yang sudah otomatis terisi.</small>

            </div>

        </div>

    </div>

</section>


<script>
    (function() {
        const WA_NUMBER = '6285151811055';

        function buildMessage() {
            const name = document.getElementById('leadName').value.trim() || '[Nama Anda]';
            const layanan = document.getElementById('leadLayanan').value || '[Belum dipilih]';
            const budget = document.getElementById('leadBudget').value || 'Belum ditentukan';
            const desc = document.getElementById('leadDesc').value.trim() || '[Belum diisi]';

            return 'Halo Empatra DigiTech! \n\n' +
                'Saya ' + name + ', tertarik untuk berkonsultasi.\n\n' +
                'Layanan: ' + layanan + '\n' +
                'Estimasi Budget: ' + budget + '\n' +
                'Kebutuhan: ' + desc + '\n\n' +
                'Mohon info lebih lanjut. Terima kasih!';
        }

        function isValid() {
            const name = document.getElementById('leadName').value.trim();
            const layanan = document.getElementById('leadLayanan').value;
            const desc = document.getElementById('leadDesc').value.trim();
            return !!(name && layanan && desc);
        }

        function updatePreview() {
            const preview = document.getElementById('waPreviewText');
            const btn = document.getElementById('waSendLeadBtn');
            if (preview) preview.innerText = buildMessage();
            if (btn) btn.disabled = !isValid();
        }

        document.addEventListener('DOMContentLoaded', function() {
            ['leadName', 'leadLayanan', 'leadBudget', 'leadDesc'].forEach(function(id) {
                const el = document.getElementById(id);
                if (!el) return;
                el.addEventListener('input', updatePreview);
                el.addEventListener('change', updatePreview);
            });

            const btn = document.getElementById('waSendLeadBtn');
            if (btn) {
                btn.addEventListener('click', function() {
                    if (!isValid()) return;
                    const link = 'https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(buildMessage());
                    window.open(link, '_blank', 'noopener');
                });
            }

            updatePreview();
        });
    })();
</script>


<style>
    /* =========================================
       KONTAK SECTION
    ========================================= */

    .kontak-section {
        position: relative;

        width: 100%;

        padding: 130px 20px 70px;

        background: #f6f8fb;
    }

    .kontak-container {
        width: min(1100px, 100%);

        margin: 0 auto;
    }


    /* =========================================
       HEADER
    ========================================= */

    .kontak-header {
        text-align: center;

        margin-bottom: 45px;
    }

    .kontak-label {
        display: block;

        margin-bottom: 4px;

        color: #a91e2a;

        font-size: 12px;
        font-weight: 800;

        letter-spacing: .9px;
    }

    .kontak-title {
        margin: 0;

        color: #123567;

        font-size: clamp(28px, 3.4vw, 38px);
        font-weight: 800;

        letter-spacing: -.8px;
    }

    .kontak-subtitle {
        max-width: 560px;

        margin: 10px auto 0;

        color: #596474;

        font-size: 14px;
        line-height: 1.6;
    }


    /* =========================================
       GRID
    ========================================= */

    .kontak-grid {
        display: grid;

        grid-template-columns: 0.85fr 1.15fr;

        gap: 30px;

        align-items: start;
    }


    /* =========================================
       INFO PANEL
    ========================================= */

    .kontak-info {
        padding: 32px 28px;

        background: #123567;

        border-radius: 14px;

        color: #ffffff;
    }

    .kontak-info-title {
        margin: 0 0 8px;

        font-size: 19px;
        font-weight: 800;
    }

    .kontak-info-desc {
        margin: 0 0 22px;

        color: rgba(255, 255, 255, .72);

        font-size: 12.5px;
        line-height: 1.6;
    }

    .kontak-info-list {
        display: flex;
        flex-direction: column;

        gap: 14px;
    }

    .kontak-info-item {
        display: flex;
        align-items: flex-start;

        gap: 12px;

        color: #ffffff;

        text-decoration: none;
    }

    .kontak-info-item strong {
        display: block;

        font-size: 12.5px;
        font-weight: 700;
    }

    .kontak-info-item small {
        display: block;

        margin-top: 2px;

        color: rgba(255, 255, 255, .72);

        font-size: 11.5px;
        line-height: 1.4;
    }

    a.kontak-info-item:hover strong {
        text-decoration: underline;
    }

    .kontak-info-icon {
        flex: 0 0 34px;

        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, .12);

        border-radius: 8px;
    }

    .kontak-info-icon svg {
        width: 16px;
        height: 16px;

        stroke: currentColor;
        stroke-width: 1.7;

        stroke-linecap: round;
        stroke-linejoin: round;
    }


    /* =========================================
       WHATSAPP LEAD FORM PANEL
    ========================================= */

    .kontak-form-wrapper {
        padding: 32px 28px;

        background: #ffffff;

        border: 1px solid #e2e6eb;
        border-radius: 14px;
    }

    .kontak-wa-header {
        display: flex;
        align-items: flex-start;

        gap: 14px;

        margin-bottom: 22px;

        padding-bottom: 20px;

        border-bottom: 1px solid #eef1f5;
    }

    .kontak-wa-header-icon {
        flex: 0 0 42px;

        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #ffffff;

        background: #25D366;

        border-radius: 50%;
    }

    .kontak-wa-header-icon svg {
        width: 22px;
        height: 22px;

        stroke: currentColor;
        stroke-width: 1.8;

        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .kontak-wa-header-desc {
        margin: 2px 0 0;

        color: #7c8798;

        font-size: 12px;
        line-height: 1.5;
    }

    .kontak-form-row {
        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: 16px;
    }

    .kontak-form-group {
        margin-bottom: 16px;
    }

    .kontak-form-group label {
        display: block;

        margin-bottom: 6px;

        color: #142f54;

        font-size: 12.5px;
        font-weight: 700;
    }

    .kontak-form-group label span {
        color: #c3202d;
    }

    .kontak-input {
        width: 100%;

        padding: 10px 13px;

        color: #22283a;
        background: #f8f9fb;

        border: 1px solid #dde2e8;
        border-radius: 8px;

        font-family: inherit;
        font-size: 13px;

        transition: border-color .2s ease, background .2s ease;
    }

    .kontak-input:focus {
        outline: none;

        background: #ffffff;
        border-color: #123b70;
    }

    .kontak-input.is-invalid {
        border-color: #c3202d;
    }

    .kontak-textarea {
        resize: vertical;

        min-height: 110px;
    }

    .kontak-hint {
        display: block;

        margin-top: 10px;

        color: #7c8798;

        font-size: 10.5px;
        line-height: 1.5;
    }

    .kontak-error {
        display: block;

        margin-top: 5px;

        color: #c3202d;

        font-size: 10.5px;
        font-weight: 600;
    }


    /* =========================================
       WA PREVIEW BUBBLE
    ========================================= */

    .kontak-wa-preview {
        margin: 4px 0 18px;

        padding: 14px 15px;

        background: #ECE5DD;

        border-radius: 10px;
    }

    .kontak-wa-preview-label {
        margin-bottom: 8px;

        color: #128C7E;

        font-size: 10.5px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .kontak-wa-preview-bubble {
        min-height: 52px;

        padding: 12px 13px;

        background: #ffffff;

        border-radius: 8px;

        color: #22283a;

        font-size: 12.5px;
        line-height: 1.6;

        white-space: pre-line;

        box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
    }


    /* =========================================
       SUBMIT BUTTON
    ========================================= */

    .kontak-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        width: 100%;

        min-height: 44px;

        padding: 0 26px;

        color: #ffffff;
        background: #123b70;

        border: 0;
        border-radius: 8px;

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;

        transition: background .2s ease, transform .2s ease;
    }

    .kontak-submit:hover:not(:disabled) {
        background: #092d59;

        transform: translateY(-1px);
    }

    .kontak-submit-wa {
        background: #25D366;
    }

    .kontak-submit-wa:hover:not(:disabled) {
        background: #1DA851;
    }

    .kontak-submit:disabled {
        background: #cbd5e1;

        cursor: not-allowed;

        transform: none;
    }

    .kontak-submit svg {
        width: 15px;
        height: 15px;

        stroke: currentColor;
        stroke-width: 1.8;
    }


    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 900px) {

        .kontak-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {

        .kontak-section {
            padding: 110px 15px 50px;
        }

        .kontak-form-row {
            grid-template-columns: 1fr;
        }

        .kontak-info,
        .kontak-form-wrapper {
            padding: 26px 20px;
        }
    }
</style>

@endsection
