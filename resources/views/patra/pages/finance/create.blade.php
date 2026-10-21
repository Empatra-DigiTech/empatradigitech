@extends('patra.layouts.master')
@section("title","Tambah Transaksi ~ EMPATRA DIGITECH")
@section("title_breadcumb","Buku Kas")
@section('breadcumb', 'Buku Kas')
@section('breadcumb_child', 'Tambah Transaksi')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form action="{{ route('patra.finance.store') }}" method="POST" autocomplete="off"
                            onsubmit="confirm('Apakah anda yakin ingin mengirim data ini?')">
                            @csrf

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">No. Transaksi</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" value="{{ $transactionNumber }}" disabled>
                                    <small class="form-text text-muted">Nomor dibuat otomatis saat disimpan.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Tanggal <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Jenis <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="masuk" {{ old('type') === 'masuk' ? 'selected' : '' }}>Uang Masuk</option>
                                        <option value="keluar" {{ old('type') === 'keluar' ? 'selected' : '' }}>Uang Keluar</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Deskripsi <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="description"
                                        placeholder="cth. Pelunasan Website Pak Haryono" value="{{ old('description') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Kategori <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <select name="finance_category_id" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $parent)
                                            <optgroup label="{{ $parent->name }} ({{ $parent->type === 'masuk' ? 'Masuk' : 'Keluar' }})" data-type="{{ $parent->type }}">
                                                <option value="{{ $parent->id }}" data-type="{{ $parent->type }}" {{ (string) old('finance_category_id') === (string) $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                @foreach ($parent->children as $child)
                                                    <option value="{{ $child->id }}" data-type="{{ $child->type }}" {{ (string) old('finance_category_id') === (string) $child->id ? 'selected' : '' }}>— {{ $child->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Daftar kategori mengikuti jenis (Masuk/Keluar) yang dipilih di atas. Kelola daftarnya di menu <a href="{{ route('patra.finance-category.index') }}" target="_blank">Kategori Keuangan</a>.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="amount" min="0.01" step="0.01"
                                        placeholder="0" value="{{ old('amount') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Metode Bayar <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <select name="payment_method" class="form-control" required>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>{{ $method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Catatan</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="notes" rows="2" placeholder="Opsional">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="{{ route('patra.finance.index') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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

@section('script')
<script>
    $(function () {
        function filterKategoriByType() {
            var type = $('#type').val();
            $('select[name="finance_category_id"] optgroup').each(function () {
                var show = $(this).data('type') === type;
                $(this).toggle(show);
            });
            var $selected = $('select[name="finance_category_id"] option:selected');
            if ($selected.data('type') !== type) {
                $('select[name="finance_category_id"]').val('');
            }
        }
        $('#type').on('change', filterKategoriByType);
        filterKategoriByType();
    });
</script>
@endsection
