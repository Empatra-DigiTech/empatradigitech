@extends('patra.layouts.master')
@section("title","FAQ ~ EMPATRA DIGITECH")
@section("title_breadcumb","FAQ")
@section('breadcumb', 'FAQ')
@section('breadcumb_child', 'Edit')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.faq.update', $result->id) }}" method="post" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Pertanyaan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="question"
                                                value="{{ old('question', $result->question) }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Jawaban <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="answer" rows="4" required>{{ old('answer', $result->answer) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Kategori</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="kategori"
                                                value="{{ old('kategori', $result->kategori) }}">
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
                                    <a href="{{ route('patra.faq.index') }}" class="btn btn-warning"><i
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
