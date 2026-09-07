{{--
    FIXED: $table_client_logo sudah di-fetch di HomeController@index sejak awal
    (query jalan tiap homepage load), tapi tidak ada partial manapun yang
    merendernya. Partial ini menampilkan logo klien sebagai strip "dipercaya
    oleh" di homepage. Kalau datanya kosong, section ini otomatis disembunyikan
    (tidak ada elemen kosong yang mengganggu layout).
--}}
@if(isset($table_client_logo) && $table_client_logo->count())
<section class="clients-section" id="clients">

    <div class="clients-container">

        <p class="clients-heading">
            Dipercaya oleh berbagai klien dan mitra bisnis
        </p>

        <div class="clients-track">

            @foreach($table_client_logo as $client)
                @if(!empty($client->website_url))
                    <a href="{{ $client->website_url }}" target="_blank" rel="noopener" class="clients-logo" title="{{ $client->nama_client }}">
                        <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->nama_client }}" loading="lazy">
                    </a>
                @else
                    <span class="clients-logo" title="{{ $client->nama_client }}">
                        <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->nama_client }}" loading="lazy">
                    </span>
                @endif
            @endforeach

        </div>

    </div>

</section>


<style>
    /* =========================================
       CLIENTS SECTION
    ========================================= */

    .clients-section {
        width: 100%;

        padding: 34px 20px;

        background: #f6f8fb;

        border-top: 1px solid #e9edf2;
        border-bottom: 1px solid #e9edf2;
    }

    .clients-container {
        width: min(1200px, 100%);

        margin: 0 auto;
    }

    .clients-heading {
        margin: 0 0 20px;

        color: #7c8798;

        font-size: 11.5px;
        font-weight: 700;

        text-align: center;

        letter-spacing: .4px;

        text-transform: uppercase;
    }

    .clients-track {
        display: flex;
        flex-wrap: wrap;

        align-items: center;
        justify-content: center;

        gap: 34px 46px;
    }

    .clients-logo {
        display: flex;
        align-items: center;
        justify-content: center;

        height: 38px;

        filter: grayscale(1);
        opacity: .6;

        transition: filter .2s ease, opacity .2s ease, transform .2s ease;
    }

    .clients-logo:hover {
        filter: grayscale(0);
        opacity: 1;

        transform: translateY(-2px);
    }

    .clients-logo img {
        max-height: 38px;
        max-width: 130px;

        object-fit: contain;
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 600px) {

        .clients-section {
            padding: 26px 15px;
        }

        .clients-track {
            gap: 24px 30px;
        }

        .clients-logo {
            height: 30px;
        }

        .clients-logo img {
            max-height: 30px;
            max-width: 100px;
        }
    }
</style>
@endif
