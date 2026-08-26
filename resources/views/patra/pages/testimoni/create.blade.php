@extends('patra.layouts.master')
@section("title","Testimoni ~ EMPATRA DIGITECH")
@section("title_breadcumb","Testimoni")
@section('breadcumb', 'Testimoni')
@section('breadcumb_child', 'Create')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            Gunakan hanya testimoni asli dari client yang benar-benar pernah menggunakan jasa Anda. Jangan gunakan data rekaan/dummy.
                        </div>
                        <form action="{{ route('patra.testimoni.store') }}" method="POST" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Nama Client <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="nama_client"
                                                placeholder="cth. Budi Santoso" value="{{ old('nama_client') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Jabatan</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="jabatan"
                                                placeholder="cth. Owner, Marketing Manager" value="{{ old('jabatan') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Perusahaan</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="perusahaan"
                                                placeholder="cth. PT Sejahtera Abadi" value="{{ old('perusahaan') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Rating <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <select class="form-control" name="rating" required>
                                                @for($i=5;$i>=1;$i--)
                                                    <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Isi Testimoni <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="testimoni" rows="4"
                                                placeholder="Tulis testimoni asli dari client di sini..." required>{{ old('testimoni') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto Client</label>
                                        <div class="col-md-10">
                                            <input type="file" class="form-control" name="foto">
                                            <small class="form-text text-muted">Opsional. Pastikan sudah mendapat izin dari client untuk menampilkan fotonya.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Urutan</label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" name="urutan"
                                                placeholder="0" value="{{ old('urutan', 0) }}">
                                            <small class="form-text text-muted">Angka kecil tampil lebih dulu.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-10">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_active"
                                                    name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">Tampilkan di website</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.testimoni.index') }}" class="btn btn-warning"><i
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
