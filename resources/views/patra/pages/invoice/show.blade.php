@extends('patra.layouts.master')
@section("title","Detail Invoice ~ EMPATRA DIGITECH")
@section("title_breadcumb","Invoice")
@section('breadcumb', 'Invoice')
@section('breadcumb_child', 'Detail')

@php
    $company = App\Models\Pengaturan::first();
@endphp

@section('css')
<style>
    /* Style tetap sama */
    .invoice-box {
        max-width: 900px;
        margin: auto;
        padding: 30px;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .invoice-title h1 {
        font-size: 36px;
        margin: 0;
        color: #333;
    }

    .invoice-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .info-section h4 {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .info-section p {
        margin: 5px 0;
        color: #333;
    }

    .invoice-table {
        width: 100%;
        margin: 30px 0;
        border-collapse: collapse;
    }

    .invoice-table thead {
        background: #1a237e;
        color: white;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 12px;
        text-align: left;
    }

    .invoice-table tbody tr {
        border-bottom: 1px solid #eee;
    }

    .totals-table {
        margin-left: auto;
        width: 300px;
        margin-top: 20px;
    }

    .totals-table tr td {
        padding: 8px 0;
    }

    .totals-table tr.total-row td {
        border-top: 2px solid #333;
        padding-top: 15px;
        font-weight: bold;
        font-size: 18px;
    }

    .notes-section {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    @media print {
        .no-print {
            display: none;
        }

        .invoice-box {
            box-shadow: none;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row no-print mb-3">
        <div class="col-12">
            <a href="{{ route('patra.invoice.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('patra.invoice.edit', $result->id) }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('patra.invoice.download', $result->id) }}" class="btn btn-success">
                <i class="fa fa-download"></i> Download PDF
            </a>
            <button onclick="window.print()" class="btn btn-info">
                <i class="fa fa-print"></i> Cetak
            </button>
            <button class="btn btn-danger btn-delete">
                <i class="fa fa-trash"></i> Hapus
            </button>
        </div>
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                @if($company && $company->website_logo)
                    <img src="{{ asset('storage/' . $company->website_logo) }}"
                         alt="Logo" style="max-width: 150px; max-height: 80px; margin-bottom: 15px; display: block;">
                @endif
                @if($company)
                    <h3>{{ $company->website_name }}</h3>
                    @if($company->website_address)
                        <p style="white-space: pre-line;">{{ $company->website_address }}</p>
                    @endif
                    @if($company->website_phone)
                        <p style="margin: 5px 0;"><i class="fa fa-phone"></i> {{ $company->website_phone }}</p>
                    @endif
                    @if($company->website_email)
                        <p style="margin: 5px 0;"><i class="fa fa-envelope"></i> {{ $company->website_email }}</p>
                    @endif
                @endif
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p style="font-size: 18px; color: #666;"># {{ $result->invoice_number }}</p>
                @if($result->balance_due <= 0)
                    <span class="badge badge-success">LUNAS</span>
                @elseif($result->due_date && \Carbon\Carbon::parse($result->due_date)->isPast())
                    <span class="badge badge-danger">JATUH TEMPO</span>
                @else
                    <span class="badge badge-warning">BELUM LUNAS</span>
                @endif
            </div>
        </div>

        <div class="invoice-info">
            <div>
                <div class="info-section mb-3">
                    <h4>TAGIH KE:</h4>
                    <p><strong>{{ $result->bill_to_name }}</strong></p>
                    <p style="white-space: pre-line;">{{ $result->bill_to_address }}</p>
                </div>

                @if($result->ship_to_name)
                <div class="info-section">
                    <h4>KIRIM KE:</h4>
                    <p><strong>{{ $result->ship_to_name }}</strong></p>
                    <p style="white-space: pre-line;">{{ $result->ship_to_address }}</p>
                </div>
                @endif
            </div>

            <div>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0; color: #666;">Tanggal Invoice:</td>
                        <td style="text-align: right; padding: 8px 0;">
                            <strong>{{ Carbon\Carbon::parse($result->invoice_date)->format('d/m/Y') }}</strong>
                        </td>
                    </tr>
                    @if($result->payment_terms)
                    <tr>
                        <td style="padding: 8px 0; color: #666;">Syarat Pembayaran:</td>
                        <td style="text-align: right; padding: 8px 0;">
                            <strong>{{ $result->payment_terms }}</strong>
                        </td>
                    </tr>
                    @endif
                    @if($result->due_date)
                    <tr>
                        <td style="padding: 8px 0; color: #666;">Jatuh Tempo:</td>
                        <td style="text-align: right; padding: 8px 0;">
                            <strong>{{ Carbon\Carbon::parse($result->due_date)->format('d/m/Y') }}</strong>
                        </td>
                    </tr>
                    @endif
                    @if($result->po_number)
                    <tr>
                        <td style="padding: 8px 0; color: #666;">No. PO:</td>
                        <td style="text-align: right; padding: 8px 0;">
                            <strong>{{ $result->po_number }}</strong>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 50%;">DESKRIPSI</th>
                    <th class="text-center" style="width: 15%;">JUMLAH</th>
                    <th class="text-right" style="width: 15%;">HARGA</th>
                    <th class="text-right" style="width: 20%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @if($result->items)
                    @foreach($result->items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ $result->formatCurrency($item['rate']) }}</td>
                        <td class="text-right">{{ $result->formatCurrency($item['amount']) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">{{ $result->formatCurrency($result->subtotal) }}</td>
            </tr>
            @if($result->tax_percentage > 0)
            <tr>
                <td>Pajak ({{ $result->tax_percentage }}%):</td>
                <td style="text-align: right;">{{ $result->formatCurrency($result->tax_amount) }}</td>
            </tr>
            @endif
            @if($result->discount_amount > 0)
            <tr>
                <td>Diskon:</td>
                <td style="text-align: right;">- {{ $result->formatCurrency($result->discount_amount) }}</td>
            </tr>
            @endif
            @if($result->shipping_amount > 0)
            <tr>
                <td>Ongkir:</td>
                <td style="text-align: right;">{{ $result->formatCurrency($result->shipping_amount) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL:</td>
                <td style="text-align: right;">{{ $result->formatCurrency($result->total) }}</td>
            </tr>
            @if($result->amount_paid > 0)
            <tr>
                <td>Dibayar:</td>
                <td style="text-align: right;">- {{ $result->formatCurrency($result->amount_paid) }}</td>
            </tr>
            <tr class="total-row">
                <td>SISA:</td>
                <td style="text-align: right;">{{ $result->formatCurrency($result->balance_due) }}</td>
            </tr>
            @endif
        </table>

        @if($result->notes)
        <div class="notes-section">
            <h4>CATATAN:</h4>
            <p style="white-space: pre-line;">{{ $result->notes }}</p>
        </div>
        @endif

        @if($result->terms)
        <div class="notes-section">
            <h4>SYARAT & KETENTUAN:</h4>
            <p style="white-space: pre-line;">{{ $result->terms }}</p>
        </div>
        @endif
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
<script>
    $(document).on("click",".btn-delete",function(){
        if(confirm("Apakah anda yakin ingin menghapus invoice ini?")){
            $("#frmDelete").attr("action", "{{ route('patra.invoice.destroy', $result->id) }}");
            $("#frmDelete").submit();
        }
    })
</script>
@endsection
