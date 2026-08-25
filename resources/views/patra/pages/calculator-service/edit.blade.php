@extends('patra.layouts.master')
@section("title","Kalkulator Layanan ~ EMPATRA DIGITECH")
@section("title_breadcumb","Kalkulator Layanan")
@section('breadcumb', 'Kalkulator Layanan')
@section('breadcumb_child', 'Edit')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.calculator-service.update', $result->id) }}" method="post" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Nama Layanan <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="nama_layanan"
                                        value="{{ old('nama_layanan', $result->nama_layanan) }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="harga_dasar"
                                        value="{{ old('harga_dasar', $result->harga_dasar) }}" min="0" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Harga per Halaman (Rp) <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="harga_per_halaman"
                                        value="{{ old('harga_per_halaman', $result->harga_per_halaman) }}" min="0" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Deskripsi</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="deskripsi" rows="2">{{ old('deskripsi', $result->deskripsi) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Urutan</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="urutan" value="{{ old('urutan', $result->urutan) }}">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label class="col-md-2 col-form-label">Status</label>
                                <div class="col-md-10">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $result->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Tampilkan di kalkulator</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.calculator-service.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
