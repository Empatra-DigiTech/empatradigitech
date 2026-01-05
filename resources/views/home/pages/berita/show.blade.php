@extends('home.layouts.master')
@section("title","". $result->title ." | EMPATRA DIGITECH")
@section('css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .info-section {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-section h4 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .info-item {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            min-width: 100px;
            color: #6c757d;
        }
        .info-value {
            flex: 1;
        }
        .section-title {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('content')
<br>
<br>
<div class="container text-justify">
    <div class="row mx-1">
        <div class="col-12 col-lg-9">
            <img src="{{ asset('storage/' . $result->image) }}" class="card-img-top mb-3" alt="">
            <h1 class=""><b>{{ $result->title }}</b></h1>
            <div class="text-black-50">
                <p>{{ Carbon\Carbon::parse($result->date)->translatedFormat('l,d F Y') }} by {{ $result->creator }}</p>
            </div>

            <!-- Info Section -->
            @if($result->klien || $result->industry || $result->layanan || $result->brand)
            <div class="info-section">
                <h4>Informasi Umum</h4>
                @if($result->klien)
                <div class="info-item">
                    <span class="info-label">Klien:</span>
                    <span class="info-value">{{ $result->klien }}</span>
                </div>
                @endif
                @if($result->industry)
                <div class="info-item">
                    <span class="info-label">Industri:</span>
                    <span class="info-value">{{ $result->industry }}</span>
                </div>
                @endif
                @if($result->layanan)
                <div class="info-item">
                    <span class="info-label">Layanan:</span>
                    <span class="info-value">{{ $result->layanan }}</span>
                </div>
                @endif
                @if($result->brand)
                <div class="info-item">
                    <span class="info-label">Brand:</span>
                    <span class="info-value">{{ $result->brand }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- Description -->
            <style>
                img {
                    max-width: 100%;
                    height: auto;
                }
                .attachment__caption {
                    display: none !important;
                }
            </style>
            <div class="section-content">
                {!! $result->renderTrix("content") !!}
            </div>

            <!-- Tantangan Section -->
            @if($result->tantangan)
            <h3 class="section-title">Tantangan</h3>
            <p>{!! nl2br(e($result->tantangan)) !!}</p>
            @endif

            <!-- Solusi Section -->
            @if($result->solusi)
            <h3 class="section-title">Solusi</h3>
            <p>{!! nl2br(e($result->solusi)) !!}</p>
            @endif

            <!-- Fitur Section -->
            @if($result->fitur)
            <h3 class="section-title">Fitur</h3>
            <p>{!! nl2br(e($result->fitur)) !!}</p>
            @endif

            <!-- Share Buttons -->
            <p class="demo mt-4">
                <p>Bagikan Juga</p>
                <button type="button" class="btn btn-icon btn-round btn-primary" id="share-facebook">
                    <i class='bx bxl-facebook'></i>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-success" id="share-whatsapp">
                    <i class='bx bxl-whatsapp'></i>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-info" id="share-twitter">
                    <i class='bx bxl-twitter'></i>
                </button>
            </p>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-3">
            <h2><b>Berita Lainnya</b></h2>
            @forelse($except_result as $index => $row)
            <div class="card-body">
                <div class="">
                    <div class="">
                        <img src="{{ asset('storage/' . $row->image) }}" class="card-img-top" alt="">
                    </div>
                    <div class="flex-1 ms-3 pt-1">
                        <a href="{{ route('home.berita.show', $row->id) }}"><h6 class="text-uppercase fw-bold mb-1">{{ $row->title }}</h6></a>
                    </div>
                    <div class="float-end pt-1">
                        <small class="text-muted">{{ Carbon\Carbon::parse($row->date)->diffForHumans(null, true).' yang lalu';}}</small>
                    </div>
                </div>
            </div>
            <div class="card-footer m-2">
            </div>
            @empty
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Facebook Share
    document.getElementById('share-facebook').onclick = function() {
        let url = encodeURIComponent(window.location.href);
        let facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
        window.open(facebookShareUrl, '_blank');
    };

    // WhatsApp Share
    document.getElementById('share-whatsapp').onclick = function() {
        let text = encodeURIComponent(document.title + " " + window.location.href);
        let whatsappShareUrl = `https://api.whatsapp.com/send?text=${text}`;
        window.open(whatsappShareUrl, '_blank');
    };

    // Twitter Share
    document.getElementById('share-twitter').onclick = function() {
        let text = encodeURIComponent(document.title);
        let url = encodeURIComponent(window.location.href);
        let twitterShareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
        window.open(twitterShareUrl, '_blank');
    };
</script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
@endsection