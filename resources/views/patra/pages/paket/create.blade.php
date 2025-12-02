{{-- create.blade.php --}}
@extends('patra.layouts.master')
@section('title', 'Tambah Paket')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Paket</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('patra.patra.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('patra.paket.index') }}">Paket</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
                        <form action="{{ route('patra.paket.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipe Paket <span class="text-danger">*</span></label>
                                            <select name="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="website" {{ old('tipe') == 'website' ? 'selected' : '' }}>Website</option>
                                                <option value="app" {{ old('tipe') == 'app' ? 'selected' : '' }}>Aplikasi Mobile</option>
                                            </select>
                                            @error('tipe')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Paket <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_paket"
                                                   class="form-control @error('nama_paket') is-invalid @enderror"
                                                   placeholder="Contoh: Basic, Pro, Premium"
                                                   value="{{ old('nama_paket') }}" required>
                                            @error('nama_paket')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tagline</label>
                                            <input type="text" name="tagline"
                                                   class="form-control @error('tagline') is-invalid @enderror"
                                                   placeholder="Contoh: Untuk pemula"
                                                   value="{{ old('tagline') }}">
                                            @error('tagline')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" name="harga"
                                                   class="form-control @error('harga') is-invalid @enderror"
                                                   placeholder="Contoh: 5000000"
                                                   value="{{ old('harga') }}" required>
                                            @error('harga')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Periode</label>
                                            <input type="text" name="periode"
                                                   class="form-control @error('periode') is-invalid @enderror"
                                                   placeholder="Contoh: Per Project, Per Tahun"
                                                   value="{{ old('periode', 'Per Project') }}">
                                            @error('periode')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Fitur <span class="text-danger">*</span></label>
                                            <div id="fitur-container">
                                                @if(old('fitur'))
                                                    @foreach(old('fitur') as $index => $fitur)
                                                    <div class="input-group mb-2 fitur-item">
                                                        <input type="text" name="fitur[]"
                                                               class="form-control"
                                                               placeholder="Contoh: Student & Teacher Dashboard"
                                                               value="{{ $fitur }}">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-danger btn-remove-fitur">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="input-group mb-2 fitur-item">
                                                        <input type="text" name="fitur[]"
                                                               class="form-control"
                                                               placeholder="Contoh: Student & Teacher Dashboard">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-danger btn-remove-fitur">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-sm btn-success mt-2" id="btn-add-fitur">
                                                <i class="fas fa-plus"></i> Tambah Fitur
                                            </button>
                                            <small class="form-text text-muted">
                                                Tambahkan fitur-fitur yang tersedia dalam paket ini
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Urutan</label>
                                            <input type="number" name="urutan"
                                                   class="form-control @error('urutan') is-invalid @enderror"
                                                   placeholder="0"
                                                   value="{{ old('urutan', 0) }}">
                                            <small class="form-text text-muted">Urutan tampilan (semakin kecil semakin depan)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="is_recommended" name="is_recommended"
                                                       value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_recommended">
                                                    Paket Rekomendasi
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="is_active" name="is_active"
                                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">
                                                    Status Aktif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('patra.paket.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Add fitur
    $('#btn-add-fitur').click(function() {
        const fiturHtml = `
            <div class="input-group mb-2 fitur-item">
                <input type="text" name="fitur[]" class="form-control" placeholder="Contoh: Custom Domain">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger btn-remove-fitur">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        $('#fitur-container').append(fiturHtml);
    });

    // Remove fitur
    $(document).on('click', '.btn-remove-fitur', function() {
        if ($('.fitur-item').length > 1) {
            $(this).closest('.fitur-item').remove();
        } else {
            alert('Minimal harus ada 1 fitur');
        }
    });
});
</script>
@endsection
