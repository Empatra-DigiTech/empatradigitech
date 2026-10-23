@extends('patra.layouts.master')
@section("title","Buku Kas ~ EMPATRA DIGITECH")
@section("title_breadcumb","Buku Kas")
@section("breadcumb","Buku Kas")
@section("content")

{{-- Warna dipilih agar kontras teks >= 4.5:1 (WCAG AA); bawaan bg-success/text-success hanya ~3.1:1. --}}
<style>
    .fin-card-in { background: #146c43; }
    .fin-card-out { background: #b02e36; }
    .fin-card-saldo { background: #0f3c5d; }
    .fin-in { color: #146c43; }
    .fin-out { color: #b02e36; }
</style>

@php
    $formatRp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp

<div class="row">
    <div class="col-lg-4">
        <div class="card text-white fin-card-in">
            <div class="card-body">
                <h6 class="text-white mb-1">Total Masuk (hasil filter)</h6>
                <h4 class="mb-0">{{ $formatRp($totalMasuk) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card text-white fin-card-out">
            <div class="card-body">
                <h6 class="text-white mb-1">Total Keluar (hasil filter)</h6>
                <h4 class="mb-0">{{ $formatRp($totalKeluar) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card text-white fin-card-saldo">
            <div class="card-body">
                <h6 class="text-white mb-1">Saldo Kas Saat Ini</h6>
                <h4 class="mb-0">{{ $formatRp($saldoSaatIni) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <a href="{{route('patra.finance.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Transaksi</a>
                        <a href="{{route('patra.finance-category.index')}}" class="btn btn-secondary"><i class="fa fa-tags"></i> Kelola Kategori</a>
                        <a href="{{route('patra.finance.period.index')}}" class="btn btn-info"><i class="fa fa-lock"></i> Periode</a>
                        <a href="{{route('patra.finance.trash')}}" class="btn btn-outline-secondary"><i class="fa fa-trash"></i> Sampah @if($trashedCount > 0)<span class="badge badge-secondary">{{ $trashedCount }}</span>@endif</a>
                        <a href="{{route('patra.finance.index')}}" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset Filter</a>
                    </div>
                </div>

                <form method="GET" action="{{ route('patra.finance.index') }}" class="row g-2 mb-3">
                    <div class="col-lg-3 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Cari deskripsi..." value="{{ $filterSearch }}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <select name="type" class="form-control">
                            <option value="">Semua Jenis</option>
                            <option value="masuk" {{ $filterType === 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ $filterType === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <select name="kategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $parent)
                                <optgroup label="{{ $parent->name }} ({{ $parent->type === 'masuk' ? 'Masuk' : 'Keluar' }})">
                                    <option value="{{ $parent->id }}" {{ (string) $filterCategory === (string) $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                    @foreach ($parent->children as $child)
                                        <option value="{{ $child->id }}" {{ (string) $filterCategory === (string) $child->id ? 'selected' : '' }}>— {{ $child->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="month" name="bulan" class="form-control" value="{{ $filterBulan }}">
                    </div>
                    <div class="col-lg-1 mb-2">
                        <select name="sort" class="form-control">
                            <option value="desc" {{ $filterSort === 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ $filterSort === 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                    <div class="col-lg-1 mb-2">
                        <button type="submit" class="btn btn-primary w-100" aria-label="Terapkan filter pencarian" title="Terapkan filter pencarian"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                </form>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>Tanggal</th>
                                    <th>No. Transaksi</th>
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th class="text-right">Masuk</th>
                                    <th class="text-right">Keluar</th>
                                    <th>Metode</th>
                                    <th class="text-right">Saldo</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $row)
                                    <tr>
                                        <td>{{ $row->date->translatedFormat('d M Y') }}</td>
                                        <td>{{ $row->transaction_number }}</td>
                                        <td>
                                            {{ $row->description }}
                                            @if($row->notes)
                                                <br><small class="text-muted">{{ $row->notes }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $row->category->name ?? '-' }}</td>
                                        <td class="text-right fin-in">
                                            @if($row->type === 'masuk') {{ $formatRp($row->amount) }} @else - @endif
                                        </td>
                                        <td class="text-right fin-out">
                                            @if($row->type === 'keluar') {{ $formatRp($row->amount) }} @else - @endif
                                        </td>
                                        <td>{{ $row->payment_method }}</td>
                                        <td class="text-right font-weight-bold">{{ $formatRp($row->saldo_berjalan) }}</td>
                                        <td>
                                            @if(in_array($row->date->format('Y-m'), $closedPeriods, true))
                                                <span class="badge badge-danger" title="Periode {{ $row->date->format('Y-m') }} sudah ditutup"><i class="fa fa-lock"></i> Tertutup</span>
                                            @else
                                            <div class="d-flex mb-1">
                                                <a href="{{route('patra.finance.edit',$row->id)}}" class="btn btn-primary btn-sm mr-1" aria-label="Ubah transaksi" title="Ubah transaksi"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm mr-1 btn-delete" data-id="{{$row->id}}" aria-label="Hapus transaksi" title="Hapus transaksi" role="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada transaksi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {!!$table->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click",".btn-delete",function(){
            let id = $(this).data("id");
            if(confirm("Hapus transaksi ini? Transaksi dipindahkan ke Sampah (bisa dipulihkan) dan saldo berjalan dihitung ulang.")){
                $("#frmDelete").attr("action", "{{ route('patra.finance.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
