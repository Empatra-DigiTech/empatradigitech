@extends('patra.layouts.master')
@section("title","Blog ~ EMPATRA DIGITECH")
@section("title_breadcumb","Blog")
@section('breadcumb', 'Blog')
@section('breadcumb_child', 'Edit')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.blog.update', $result->id) }}" method="post" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Judul <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="title" placeholder="Judul"
                                                value="{{ old('title', $result->title) }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Kategori <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <select class="form-control" name="kategori" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach(['Estimasi Biaya','Perbandingan Sistem','UMKM & Teknologi','Tips & Insight'] as $kategoriOption)
                                                    <option value="{{ $kategoriOption }}" {{ old('kategori', $result->kategori) == $kategoriOption ? 'selected' : '' }}>{{ $kategoriOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Ringkasan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="excerpt" rows="2" maxlength="160"
                                                required>{{ old('excerpt', $result->excerpt) }}</textarea>
                                            <small class="form-text text-muted">Muncul di kartu listing blog dan di hasil pencarian Google.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-5">
                                        <label class="col-md-2 col-form-label" for="description">Isi Artikel <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            @trix($result, 'content')
                                        </div>
                                    </div>
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
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-10">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_published"
                                                    name="is_published" value="1" {{ old('is_published', $result->is_published) ? 'checked' : '' }}>
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
