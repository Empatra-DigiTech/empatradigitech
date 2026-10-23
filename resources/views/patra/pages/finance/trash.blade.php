@extends('patra.layouts.master')
@section("title","Sampah Buku Kas ~ EMPATRA DIGITECH")
@section("title_breadcumb","Sampah Buku Kas")
@section("breadcumb","Buku Kas")
@section("breadcumb_child","Sampah")
@section("content")

@php
    $formatRp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp

<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <a href="{{ route('patra.finance.index') }}" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Kembali ke Buku Kas</a>

                <div class="alert alert-info">
                    Transaksi yang dihapus tidak ikut dihitung di saldo, tetapi tersimpan di sini dan bisa dipulihkan
                    (selama periodenya belum ditutup). Nomor transaksinya tidak dipakai ulang.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>Tanggal</th>
                            <th>No. Transaksi</th>
                            <th>Deskripsi</th>
                            <th>Jenis</th>
                            <th class="text-right">Jumlah</th>
                            <th>Dihapus</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @forelse ($table as $row)
                            <tr>
                                <td>{{ $row->date->translatedFormat('d M Y') }}</td>
                                <td>{{ $row->transaction_number }}</td>
                                <td>{{ $row->description }}</td>
                                <td>{{ ucfirst($row->type) }}</td>
                                <td class="text-right">{{ $formatRp($row->amount) }}</td>
                                <td>
                                    {{ $row->deleted_at->format('d/m/Y H:i') }}<br>
                                    <small class="text-muted">{{ $deleters[$row->deleted_by] ?? '-' }}</small>
                                </td>
                                <td>
                                    @if(in_array($row->date->format('Y-m'), $closedPeriods, true))
                                        <span class="badge badge-danger"><i class="fa fa-lock"></i> Periode tertutup</span>
                                    @else
                                        <form method="POST" action="{{ route('patra.finance.restore', $row->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Pulihkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">Sampah kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {!! $table->links() !!}
            </div>
        </div>
    </div>
</div>

@endsection
