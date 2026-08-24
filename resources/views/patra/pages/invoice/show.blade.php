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
        padding: 40px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #333;
    }

    .company-info {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .logo-container {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
    }

    .logo-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .company-details h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }

    .company-details p {
        margin: 3px 0;
        color: #666;
        font-size: 12px;
        line-height: 1.6;
    }

    .invoice-title h1 {
        font-size: 36px;
        margin: 0;
        color: #1a237e;
        font-weight: bold;
    }

    .invoice-number {
        font-size: 16px;
        color: #666;
        margin-top: 10px;
    }

    .invoice-info {
        margin: 30px 0;
    }

    .invoice-parties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .info-section h4 {
        font-size: 11px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .info-section p {
        margin: 4px 0;
        color: #333;
        font-size: 14px;
    }

    .info-section strong {
        font-weight: bold;
        color: #000;
    }

    .details-table {
        width: 100%;
    }

    .details-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .details-table td {
        padding: 8px 0;
        font-size: 14px;
    }

    .details-table .label {
        color: #666;
    }

    .details-table .value {
        text-align: right;
        font-weight: bold;
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

    .invoice-table th {
        padding: 12px 10px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .invoice-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #e0e0e0;
        font-size: 14px;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: 2px solid #333;
    }

    .totals-section {
        margin-left: auto;
        width: 350px;
        margin-top: 30px;
    }

    .totals-table {
        width: 100%;
    }

    .totals-table tr td {
        padding: 8px 0;
        font-size: 14px;
    }

    .totals-table tr td:first-child {
        color: #666;
    }

    .totals-table tr td:last-child {
        text-align: right;
        font-weight: bold;
        color: #333;
    }

    .totals-table tr.total-row td {
        border-top: 2px solid #333;
        padding-top: 15px;
        font-size: 16px;
        font-weight: bold;
        color: #1a237e;
    }

    .totals-table tr.balance-row {
        background-color: #f5f5f5;
    }

    .totals-table tr.balance-row td {
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
        color: #d32f2f;
    }

    .notes-section {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .notes-section h4 {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .notes-section p {
        color: #333;
        white-space: pre-line;
        line-height: 1.6;
        font-size: 14px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
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
        <!-- Header dengan Logo -->
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
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-parties">
                <div>
                    <div class="info-section mb-3">
                        <h4>Tagih Ke:</h4>
                        <p><strong>{{ $result->bill_to_name }}</strong></p>
                        <p style="white-space: pre-line;">{{ $result->bill_to_address }}</p>
                    </div>

                    @if($result->ship_to_name)
                    <div class="info-section">
                        <h4>Kirim Ke:</h4>
                        <p><strong>{{ $result->ship_to_name }}</strong></p>
                        <p style="white-space: pre-line;">{{ $result->ship_to_address }}</p>
                    </div>
                    @endif
                </div>

                <div>
                    <table class="details-table">
                        <tr>
                            <td class="label">Tanggal Invoice:</td>
                            <td class="value">
                                {{ Carbon\Carbon::parse($result->invoice_date)->format('d/m/Y') }}
                            </td>
                        </tr>
                        @if($result->payment_terms)
                        <tr>
                            <td class="label">Syarat Pembayaran:</td>
                            <td class="value">{{ $result->payment_terms }}</td>
                        </tr>
                        @endif
                        @if($result->due_date)
                        <tr>
                            <td class="label">Jatuh Tempo:</td>
                            <td class="value">
                                {{ Carbon\Carbon::parse($result->due_date)->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endif
                        @if($result->po_number)
                        <tr>
                            <td class="label">No. PO:</td>
                            <td class="value">{{ $result->po_number }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Items Table -->
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

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>{{ $result->formatCurrency($result->subtotal) }}</td>
                </tr>
                @if($result->tax_percentage > 0)
                <tr>
                    <td>Pajak ({{ $result->tax_percentage }}%):</td>
                    <td>{{ $result->formatCurrency($result->tax_amount) }}</td>
                </tr>
                @endif
                @if($result->discount_amount > 0)
                <tr>
                    <td>Diskon:</td>
                    <td>- {{ $result->formatCurrency($result->discount_amount) }}</td>
                </tr>
                @endif
                @if($result->shipping_amount > 0)
                <tr>
                    <td>Ongkir:</td>
                    <td>{{ $result->formatCurrency($result->shipping_amount) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td>{{ $result->formatCurrency($result->total) }}</td>
                </tr>
                @if($result->amount_paid > 0)
                <tr>
                    <td>Dibayar:</td>
                    <td>- {{ $result->formatCurrency($result->amount_paid) }}</td>
                </tr>
                <tr class="balance-row">
                    <td>SISA:</td>
                    <td>{{ $result->formatCurrency($result->balance_due) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Notes -->
        @if($result->notes)
        <div class="notes-section">
            <h4>Catatan:</h4>
            <p>{{ $result->notes }}</p>
        </div>
        @endif

        <!-- Terms -->
        @if($result->terms)
        <div class="notes-section">
            <h4>Syarat & Ketentuan:</h4>
            <p>{{ $result->terms }}</p>
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
