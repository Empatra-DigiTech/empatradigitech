@extends('patra.layouts.master')
@section("title","Edit Kategori ~ EMPATRA DIGITECH")
@section("title_breadcumb","Kategori Keuangan")
@section('breadcumb', 'Kategori Keuangan')
@section('breadcumb_child', 'Edit')
@section('content')
    @php $isActive = count(old()) ? (bool) old('is_active') : (bool) $result->is_active; @endphp
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.finance-category.update', $result->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $result->name) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Jenis <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    @php $curType = old('type', $result->type); @endphp
                                    <select name="type" class="form-control" required>
                                        <option value="masuk" {{ $curType === 'masuk' ? 'selected' : '' }}>Masuk (pendapatan)</option>
                                        <option value="keluar" {{ $curType === 'keluar' ? 'selected' : '' }}>Keluar (pengeluaran)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Kategori Induk</label>
                                <div class="col-md-10">
                                    @php $curParent = old('parent_id', $result->parent_id); @endphp
                                    <select name="parent_id" class="form-control">
                                        <option value="">-- Tanpa induk (kategori utama) --</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ (string) $curParent === (string) $parent->id ? 'selected' : '' }}>{{ $parent->name }} ({{ $parent->type === 'masuk' ? 'Masuk' : 'Keluar' }})</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Isi jika ini sub kategori. Jenis sub kategori sebaiknya sama dengan induknya.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Urutan</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="urutan" min="0" value="{{ old('urutan', $result->urutan) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Aktif</label>
                                <div class="col-md-10">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $isActive ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Tampilkan di pilihan kategori transaksi</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.finance-category.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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
