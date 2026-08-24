@extends('patra.layouts.master')
@section("title","Portofolio ~ EMPATRA DIGITECH")
@section("title_breadcumb","Portofolio")
@section('css')

@endsection
@section('breadcumb', 'Portofolio')
@section('breadcumb_child', 'Create')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.portofolio.store') }}" method="POST" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <!-- Judul -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Judul <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="title" placeholder="Judul"
                                                value="{{ old('title') }}" required>
                                        </div>
                                    </div>

                                    <!-- Klien -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Klien</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="klien" placeholder="Nama Klien"
                                                value="{{ old('klien') }}">
                                        </div>
                                    </div>

                                    <!-- Industry -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Industri</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="industry" placeholder="Industri"
                                                value="{{ old('industry') }}">
                                        </div>
                                    </div>

                                    <!-- Layanan -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Layanan</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="layanan" placeholder="Layanan"
                                                value="{{ old('layanan') }}">
                                        </div>
                                    </div>

                                    <!-- Brand -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Brand</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="brand" placeholder="Brand"
                                                value="{{ old('brand') }}">
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="form-group row mb-5">
                                        <label class="col-md-2 col-form-label" for="description">Deskripsi <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            @trix(\App\Models\Portofolio::class, 'content')
                                        </div>
                                    </div>

                                    <!-- Tantangan -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Tantangan</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="tantangan" rows="4" placeholder="Deskripsikan tantangan yang dihadapi...">{{ old('tantangan') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Solusi -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Solusi</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="solusi" rows="4" placeholder="Deskripsikan solusi yang diberikan...">{{ old('solusi') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Fitur -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Fitur</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="fitur" rows="4" placeholder="Deskripsikan fitur-fitur utama...">{{ old('fitur') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Hasil -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Hasil</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="hasil" rows="4" placeholder="Deskripsikan hasil/dampak yang dicapai klien...">{{ old('hasil') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Demo URL -->
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Link Demo</label>
                                        <div class="col-md-10">
                                            <input type="url" class="form-control" name="demo_url" placeholder="https://contoh-demo-proyek.com"
                                                value="{{ old('demo_url') }}">
                                            <small class="form-text text-muted">Opsional — kosongkan jika tidak ada demo publik.</small>
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="form-group row mt-5">
                                        <label class="col-md-2 col-form-label">Image <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" name="image" accept="image/*" required>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.portofolio.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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
