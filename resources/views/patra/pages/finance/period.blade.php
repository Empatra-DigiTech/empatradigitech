@extends('patra.layouts.master')
@section("title","Periode Buku Kas ~ EMPATRA DIGITECH")
@section("title_breadcumb","Periode Buku Kas")
@section("breadcumb","Buku Kas")
@section("breadcumb_child","Periode")
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
                    Periode yang <strong>ditutup</strong> tidak bisa ditambah, diubah, dihapus, atau dipulihkan transaksinya.
                    Untuk mengubahnya, periode harus <strong>dibuka kembali</strong> dengan alasan tertulis; siapa, kapan, dan alasannya tercatat.
                </div>

                <form method="POST" action="{{ route('patra.finance.period.close') }}" class="form-inline mb-4">
                    @csrf
                    <label class="mr-2" for="period">Tutup periode</label>
                    <input type="month" id="period" name="period" class="form-control mr-2" value="{{ $defaultPeriod }}" max="{{ $maxPeriod }}" required>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-lock"></i> Tutup Periode</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-right">Transaksi</th>
                            <th class="text-right">Masuk</th>
                            <th class="text-right">Keluar</th>
                            <th>Ditutup</th>
                            <th>Dibuka kembali</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @forelse ($periods as $p)
                            <tr>
                                <td><strong>{{ $p->period }}</strong></td>
                                <td>
                                    @if($p->is_closed)
                                        <span class="badge badge-danger">Tertutup</span>
                                    @else
                                        <span class="badge badge-success">Terbuka</span>
                                    @endif
                                </td>
                                <td class="text-right">{{ $p->summary->jumlah }}</td>
                                <td class="text-right text-success">{{ $formatRp($p->summary->masuk) }}</td>
                                <td class="text-right text-danger">{{ $formatRp($p->summary->keluar) }}</td>
                                <td>
                                    @if($p->closed_at)
                                        {{ $p->closed_at->format('d/m/Y H:i') }}<br><small class="text-muted">{{ $p->closer->name ?? '-' }}</small>
                                    @else - @endif
                                </td>
                                <td>
                                    @if($p->reopened_at)
                                        {{ $p->reopened_at->format('d/m/Y H:i') }}<br><small class="text-muted">{{ $p->reopener->name ?? '-' }}: {{ $p->reopen_reason }}</small>
                                    @else - @endif
                                </td>
                                <td>
                                    @if($p->is_closed)
                                        <form method="POST" action="{{ route('patra.finance.period.reopen') }}" class="form-inline">
                                            @csrf
                                            <input type="hidden" name="period" value="{{ $p->period }}">
                                            <input type="text" name="reason" class="form-control form-control-sm mr-1" placeholder="Alasan (min. 10 karakter)" minlength="10" maxlength="500" required>
                                            <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-unlock"></i> Buka</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('patra.finance.period.close') }}">
                                            @csrf
                                            <input type="hidden" name="period" value="{{ $p->period }}">
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-lock"></i> Tutup lagi</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center">Belum ada periode yang ditutup</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
