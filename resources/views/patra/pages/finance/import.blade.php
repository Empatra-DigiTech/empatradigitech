@extends('patra.layouts.master')
@section("title","Import Excel Buku Kas ~ EMPATRA DIGITECH")
@section("title_breadcumb","Buku Kas")
@section("breadcumb","Buku Kas")
@section("breadcumb_child","Import Excel")
@section("content")
<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Format file</strong> (baris judul di bagian atas, urutan kolom bebas):<br>
                    <code>Tanggal</code>, <code>No. Transaksi</code>, <code>Deskripsi</code>, <code>Kategori</code>, <code>Sub Kategori</code>,
                    <code>Uang Masuk (Rp.)</code>, <code>Uang Keluar (Rp.)</code>, <code>Metode Bayar</code>, <code>Saldo Akhir</code>.<br>
                    Yang wajib hanya Tanggal dan Uang Masuk / Uang Keluar. Sheet lain (mis. pajak) dilewati otomatis.
                    Tidak ada yang tersimpan sebelum Anda mengonfirmasi di halaman pratinjau.
                </div>

                <form action="{{ route('patra.finance.import.preview') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="file">File <span class="text-danger">*</span></label>
                        <div class="col-md-10">
                            <input type="file" id="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted">.xlsx / .xls / .csv, maksimal 5 MB dan 2000 baris.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="date_mode">Pembacaan tanggal</label>
                        <div class="col-md-10">
                            <select id="date_mode" name="date_mode" class="form-control">
                                <option value="auto" {{ old('date_mode', 'auto') === 'auto' ? 'selected' : '' }}>Otomatis (disarankan)</option>
                                <option value="swap" {{ old('date_mode') === 'swap' ? 'selected' : '' }}>Tukar hari dan bulan pada sel tanggal Excel</option>
                                <option value="asis" {{ old('date_mode') === 'asis' ? 'selected' : '' }}>Apa adanya</option>
                            </select>
                            <small class="form-text text-muted">Excel berbahasa Inggris sering menukar hari dan bulan saat Anda mengetik dd/mm. Mode otomatis memilih tafsir yang urutan tanggalnya paling kronologis. Hasilnya bisa dicek di pratinjau.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('patra.finance.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i> Lihat Pratinjau</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
