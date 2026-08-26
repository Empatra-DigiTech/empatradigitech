@extends('patra.layouts.master')
@section("title","Testimoni ~ EMPATRA DIGITECH")
@section("title_breadcumb","Testimoni")
@section('breadcumb', 'Testimoni')
@section('breadcumb_child', 'Edit')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.testimoni.update', $result->id) }}" method="post" autocomplete="off"
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
                                        <label class="col-md-2 col-form-label">Jabatan</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="jabatan"
                                                value="{{ old('jabatan', $result->jabatan) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Perusahaan</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="perusahaan"
                                                value="{{ old('perusahaan', $result->perusahaan) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Rating <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <select class="form-control" name="rating" required>
                                                @for($i=5;$i>=1;$i--)
                                                    <option value="{{ $i }}" {{ old('rating', $result->rating) == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Isi Testimoni <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="testimoni" rows="4" required>{{ old('testimoni', $result->testimoni) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Foto Client</label>
                                        <div class="col-md-10">
                                            @if($result->foto)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$result->foto) }}" style="width:80px;height:80px;object-fit:cover;border-radius:50%;">
                                            </div>
                                            @endif
                                            <input type="file" class="form-control" name="foto">
                                            <small class="form-text text-muted">Kosongkan jika tidak diubah.</small>
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
