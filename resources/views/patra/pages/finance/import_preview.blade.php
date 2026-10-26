@extends('patra.layouts.master')
@section("title","Pratinjau Import Buku Kas ~ EMPATRA DIGITECH")
@section("title_breadcumb","Buku Kas")
@section("breadcumb","Buku Kas")
@section("breadcumb_child","Pratinjau Import")
@section("content")

@php
    $formatRp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $blocked = $summary['needs_category'] > 0;
@endphp

<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">
                <p class="mb-2">Sheet: <strong>{{ $sheet }}</strong> &middot; {{ $summary['total'] }} baris terbaca</p>
                <p class="mb-3">
                    <span class="badge badge-success">{{ $summary['ok'] }} akan diimpor</span>
                    <span class="badge badge-secondary">{{ $summary['skip'] }} dilewati</span>
                    <span class="badge badge-danger">{{ $summary['error'] }} error</span>
                    &middot; Masuk {{ $formatRp($summary['in']) }} &middot; Keluar {{ $formatRp($summary['out']) }}
                </p>

                @foreach ($warnings as $w)
                    <div class="alert alert-warning">{{ $w }}</div>
                @endforeach
                @if (count($summary['balance_mismatch']))
                    <div class="alert alert-warning">
                        Kolom <strong>Saldo Akhir</strong> di Excel tidak cocok dengan hitungan sistem pada: {{ implode(', ', array_slice($summary['balance_mismatch'], 0, 10)) }}{{ count($summary['balance_mismatch']) > 10 ? ', ...' : '' }}.
                        Periksa baris tersebut sebelum mengimpor.
                    </div>
                @endif
                @if ($blocked)
                    <div class="alert alert-danger">{{ $summary['needs_category'] }} baris belum punya kategori. Pilih kategorinya di tabel agar bisa diimpor.</div>
                @endif

                <form action="{{ route('patra.finance.import.store') }}" method="POST"
                    onsubmit="return confirm('Impor {{ $summary['ok'] }} transaksi ke Buku Kas?')">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                                <th>Baris</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>No. Transaksi</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th class="text-right">Masuk</th>
                                <th class="text-right">Keluar</th>
                                <th>Metode</th>
                            </thead>
                            <tbody>
                                @foreach ($rows as $i => $row)
                                <tr>
                                    <td>{{ $row['excel_row'] }}</td>
                                    <td>
                                        @if ($row['status'] === 'ok')
                                            <span class="badge badge-success">Impor</span>
                                            @if ($row['reason'])<br><small class="text-danger">{{ $row['reason'] }}</small>@endif
                                        @elseif ($row['status'] === 'skip')
                                            <span class="badge badge-secondary">Lewati</span><br><small class="text-muted">{{ $row['reason'] }}</small>
                                        @else
                                            <span class="badge badge-danger">Error</span><br><small class="text-danger">{{ $row['reason'] }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->translatedFormat('d M Y') : '-' }}</td>
                                    <td>{{ $row['number'] ?: 'otomatis' }}</td>
                                    <td>{{ $row['description'] }}</td>
                                    <td style="min-width: 220px">
                                        @if ($row['status'] === 'ok')
                                            <select name="category[{{ $i }}]" class="form-control form-control-sm" required aria-label="Kategori baris {{ $row['excel_row'] }}">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($categories->where('type', $row['type']) as $parent)
                                                    <optgroup label="{{ $parent->name }}">
                                                        <option value="{{ $parent->id }}" {{ (int) $row['category_id'] === $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                        @foreach ($parent->children as $child)
                                                            <option value="{{ $child->id }}" {{ (int) $row['category_id'] === $child->id ? 'selected' : '' }}>— {{ $child->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="text-muted">{{ $row['hint'] ?: '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right fin-in">{{ $row['type'] === 'masuk' && ! $row['error'] ? $formatRp($row['amount']) : '-' }}</td>
                                    <td class="text-right fin-out">{{ $row['type'] === 'keluar' && ! $row['error'] ? $formatRp($row['amount']) : '-' }}</td>
                                    <td>{{ $row['payment_method'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ route('patra.finance.import.create') }}" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary" {{ $summary['ok'] === 0 ? 'disabled' : '' }}><i class="fa fa-save"></i> Impor {{ $summary['ok'] }} Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
