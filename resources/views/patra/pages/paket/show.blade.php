{{-- show.blade.php --}}
@extends('patra.layouts.master')
@section('title', 'Detail Paket')

@section('css')
<style>
    .badge-website {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .badge-app {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .detail-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .detail-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }
    .detail-value {
        font-size: 1.1rem;
        color: #212529;
    }
    .feature-list {
        list-style: none;
        padding: 0;
    }
    .feature-list li {
        padding: 10px 15px;
        background: white;
        margin-bottom: 8px;
        border-radius: 5px;
        border-left: 3px solid #007bff;
    }
    .feature-list li i {
        color: #28a745;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Paket</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('patra.patra.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('patra.paket.index') }}">Paket</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Informasi Paket
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('patra.paket.edit', $result->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('patra.paket.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Informasi Dasar -->
                                <div class="col-md-6">
                                    <div class="detail-card">
                                        <div class="detail-label">Tipe Paket</div>
                                        <div class="detail-value">
                                            <span class="badge badge-lg {{ $result->tipe == 'website' ? 'badge-website' : 'badge-app' }}">
                                                <i class="bx {{ $result->tipe == 'website' ? 'bx-laptop' : 'bx-mobile-alt' }}"></i>
                                                {{ ucfirst($result->tipe) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-card">
                                        <div class="detail-label">Nama Paket</div>
                                        <div class="detail-value">
                                            <strong>{{ $result->nama_paket }}</strong>
                                            @if($result->is_recommended)
                                                <i class="fas fa-star text-warning ml-2" title="Recommended"></i>
                                            @endif
                                        </div>
                                    </div>

                                    @if($result->tagline)
                                    <div class="detail-card">
                                        <div class="detail-label">Tagline</div>
                                        <div class="detail-value text-muted">
                                            <em>"{{ $result->tagline }}"</em>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Harga & Status -->
                                <div class="col-md-6">
                                    <div class="detail-card">
                                        <div class="detail-label">Harga</div>
                                        <div class="detail-value">
                                            <h3 class="text-primary mb-0">{{ $result->formatted_harga }}</h3>
                                            <small class="text-muted">{{ $result->periode }}</small>
                                        </div>
                                    </div>

                                    <div class="detail-card">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value">
                                            @if($result->is_active)
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check-circle"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-secondary badge-lg">
                                                    <i class="fas fa-times-circle"></i> Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="detail-card">
                                        <div class="detail-label">Urutan Tampilan</div>
                                        <div class="detail-value">
                                            <span class="badge badge-info badge-lg">{{ $result->urutan }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fitur -->
                                <div class="col-md-12 mt-3">
                                    <div class="detail-card">
                                        <div class="detail-label mb-3">
                                            <i class="fas fa-list-ul"></i> Fitur-Fitur Paket
                                        </div>
                                        @if(!empty($result->fitur_list))
                                            <ul class="feature-list">
                                                @foreach($result->fitur_list as $fitur)
                                                    <li>
                                                        <i class="fas fa-check-circle"></i>
                                                        {{ $fitur }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted">Tidak ada fitur yang ditambahkan</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Timestamp -->
                                <div class="col-md-12 mt-3">
                                    <div class="detail-card">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="detail-label">Dibuat Pada</div>
                                                <div class="detail-value text-muted">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $result->created_at->format('d F Y, H:i') }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detail-label">Terakhir Diubah</div>
                                                <div class="detail-value text-muted">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $result->updated_at->format('d F Y, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <form action="{{ route('patra.paket.destroy', $result->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')"
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus Paket
                                </button>
                            </form>
                            <a href="{{ route('patra.paket.edit', $result->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Paket
                            </a>
                            <a href="{{ route('patra.paket.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
