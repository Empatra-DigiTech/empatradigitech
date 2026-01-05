@extends('patra.layouts.master')
@section("title","Berita ~ EMPATRA DIGITECH")
@section("title_breadcumb","Berita")
@section('breadcumb', 'Berita')
@section('breadcumb_child', 'Edit')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.berita.update', $result->id) }}" method="post" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <!-- Judul -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Judul <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="title" placeholder="Judul"
                                                value="{{ old('title', $result->title) }}" required>
                                        </div>
                                    </div>

                                    <!-- Klien -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Klien</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="klien" placeholder="Nama Klien"
                                                value="{{ old('klien', $result->klien) }}">
                                        </div>
                                    </div>

                                    <!-- Industry -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Industri</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="industry" placeholder="Industri"
                                                value="{{ old('industry', $result->industry) }}">
                                        </div>
                                    </div>

                                    <!-- Layanan -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Layanan</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="layanan" placeholder="Layanan"
                                                value="{{ old('layanan', $result->layanan) }}">
                                        </div>
                                    </div>

                                    <!-- Brand -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Brand</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="brand" placeholder="Brand"
                                                value="{{ old('brand', $result->brand) }}">
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="form-group row mb-5">
                                        <label class="col-md-2 col-form-label" for="description">Deskripsi <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            @trix($result, 'content')
                                        </div>
                                    </div>

                                    <!-- Tantangan -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Tantangan</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="tantangan" rows="4" placeholder="Deskripsikan tantangan yang dihadapi...">{{ old('tantangan', $result->tantangan) }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Solusi -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Solusi</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="solusi" rows="4" placeholder="Deskripsikan solusi yang diberikan...">{{ old('solusi', $result->solusi) }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Fitur -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Fitur</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="fitur" rows="4" placeholder="Deskripsikan fitur-fitur utama...">{{ old('fitur', $result->fitur) }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Tanggal -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Tanggal<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="date" class="form-control" name="date" placeholder="Tanggal"
                                                value="{{ old('date', $result->date) }}" required>
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Image <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" name="image" accept="image/*">
                                            <p class="text-info"
                                                style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                                <small><i>Kosongkan jika tidak diubah</i></small></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.berita.index') }}" class="btn btn-warning"><i
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