@extends('patra.layouts.master')
@section("title","Client / Partner ~ EMPATRA DIGITECH")
@section("title_breadcumb","Client / Partner")
@section('breadcumb', 'Client / Partner')
@section('breadcumb_child', 'Edit')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.client-logo.update', $result->id) }}" method="post" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Nama Client <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="nama_client"
                                                value="{{ old('nama_client', $result->nama_client) }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Logo</label>
                                        <div class="col-md-10">
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$result->logo) }}" style="width:100px;height:60px;object-fit:contain;">
                                            </div>
                                            <input type="file" class="form-control" name="logo">
                                            <small class="form-text text-muted">Kosongkan jika tidak diubah.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Website Client</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="website_url"
                                                value="{{ old('website_url', $result->website_url) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Urutan</label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" name="urutan"
                                                value="{{ old('urutan', $result->urutan) }}">
                                            <small class="form-text text-muted">Angka kecil tampil lebih dulu.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-10">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_active"
                                                    name="is_active" value="1" {{ old('is_active', $result->is_active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">Tampilkan di website</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.client-logo.index') }}" class="btn btn-warning"><i
                                            class="fa fa-arrow-left"></i> Kembali</a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                        Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
