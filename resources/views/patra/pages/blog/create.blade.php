@extends('patra.layouts.master')
@section("title","Blog ~ EMPATRA DIGITECH")
@section("title_breadcumb","Blog")
@section('css')

@endsection
@section('breadcumb', 'Blog')
@section('breadcumb_child', 'Create')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.blog.store') }}" method="POST" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Judul <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="title" placeholder="cth. Berapa Estimasi Biaya Membuat Website untuk UMKM?"
                                                value="{{ old('title') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Kategori <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <select class="form-control" name="kategori" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach(['Estimasi Biaya','Perbandingan Sistem','UMKM & Teknologi','Tips & Insight'] as $kategoriOption)
                                                    <option value="{{ $kategoriOption }}" {{ old('kategori') == $kategoriOption ? 'selected' : '' }}>{{ $kategoriOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Ringkasan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="excerpt" rows="2" maxlength="160"
                                                placeholder="Ringkasan singkat 1-2 kalimat — juga dipakai sebagai meta description SEO (maks 160 karakter)" required>{{ old('excerpt') }}</textarea>
                                            <small class="form-text text-muted">Muncul di kartu listing blog dan di hasil pencarian Google.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-5">
                                        <label class="col-md-2 col-form-label" for="description">Isi Artikel <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            @trix(\App\Models\Blog::class, 'content')
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Image <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" name="image" accept="image/*"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-10">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_published"
                                                    name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_published">Terbitkan sekarang</label>
                                            </div>
                                            <small class="form-text text-muted">Matikan untuk menyimpan sebagai draft (tidak tampil di halaman publik).</small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.blog.index') }}" class="btn btn-warning"><i
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
