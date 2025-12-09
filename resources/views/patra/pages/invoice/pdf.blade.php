<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .invoice-box {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
        }

        .invoice-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .invoice-header table {
            width: 100%;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 36px;
            color: #1a237e;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 16px;
            color: #666;
        }

        .company-info {
            text-align: left;
        }

        .company-info h2 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #333;
        }

        .company-info p {
            color: #666;
            margin: 3px 0;
        }

        .invoice-info {
            margin: 30px 0;
        }

        .invoice-info table {
            width: 100%;
        }

        .invoice-info td {
            vertical-align: top;
            padding: 10px;
        }

        .info-section h4 {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .info-section p {
            margin: 4px 0;
            color: #333;
        }

        .info-section strong {
            font-size: 14px;
            color: #000;
        }

        .details-table {
            width: 100%;
            margin-top: 10px;
        }

        .details-table td {
            padding: 5px 0;
        }

        .details-table .label {
            color: #666;
            width: 150px;
        }

        .details-table .value {
            text-align: right;
            font-weight: bold;
            color: #333;
        }

        .items-table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }

        .items-table thead {
            background-color: #1a237e;
            color: white;
        }

        .items-table th {
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #333;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            margin-top: 30px;
            float: right;
            width: 300px;
        }

        .totals-table {
            width: 100%;
        }

        .totals-table td {
            padding: 8px 0;
        }

        .totals-table .label {
            color: #666;
        }

        .totals-table .amount {
            text-align: right;
            font-weight: bold;
        }

        .total-row {
            border-top: 2px solid #333;
            padding-top: 10px;
        }

        .total-row td {
            padding-top: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #1a237e;
        }

        .balance-row {
            background-color: #f5f5f5;
            padding: 5px 0;
        }

        .balance-row td {
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
        }

        .notes-section {
            clear: both;
            margin-top: 50px;
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
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-paid {
            background-color: #4caf50;
            color: white;
        }

        .badge-unpaid {
            background-color: #ff9800;
            color: white;
        }

        .badge-overdue {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header -->
        <div class="invoice-header">
            <table>
                <tr>
                    <td class="company-info" style="width: 50%;">
                        @if($invoice->from_name)
                            <h2>{{ $invoice->from_name }}</h2>
                            <p style="white-space: pre-line;">{{ $invoice->from_address }}</p>
                        @endif
                    </td>
                    <td class="invoice-title" style="width: 50%;">
                        <h1>INVOICE</h1>
                        <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                        @if($invoice->balance_due <= 0)
                            <div style="margin-top: 10px;">
                                <span class="badge badge-paid">LUNAS</span>
                            </div>
                        @elseif($invoice->due_date && \Carbon\Carbon::parse($invoice->due_date)->isPast())
                            <div style="margin-top: 10px;">
                                <span class="badge badge-overdue">JATUH TEMPO</span>
                            </div>
                        @else
                            <div style="margin-top: 10px;">
                                <span class="badge badge-unpaid">BELUM LUNAS</span>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="info-section">
                            <h4>Tagih Ke:</h4>
                            <p><strong>{{ $invoice->bill_to_name }}</strong></p>
                            <p style="white-space: pre-line;">{{ $invoice->bill_to_address }}</p>
                        </div>

                        @if($invoice->ship_to_name)
                        <div class="info-section" style="margin-top: 20px;">
                            <h4>Kirim Ke:</h4>
                            <p><strong>{{ $invoice->ship_to_name }}</strong></p>
                            <p style="white-space: pre-line;">{{ $invoice->ship_to_address }}</p>
                        </div>
                        @endif
                    </td>
                    <td style="width: 50%;">
                        <table class="details-table">
                            <tr>
                                <td class="label">Tanggal Invoice:</td>
                                <td class="value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                            </tr>
                            @if($invoice->payment_terms)
                            <tr>
                                <td class="label">Syarat Pembayaran:</td>
                                <td class="value">{{ $invoice->payment_terms }}</td>
                            </tr>
                            @endif
                            @if($invoice->due_date)
                            <tr>
                                <td class="label">Jatuh Tempo:</td>
                                <td class="value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                            @if($invoice->po_number)
                            <tr>
                                <td class="label">No. PO:</td>
                                <td class="value">{{ $invoice->po_number }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">DESKRIPSI</th>
                    <th class="text-center" style="width: 15%;">JUMLAH</th>
                    <th class="text-right" style="width: 15%;">HARGA</th>
                    <th class="text-right" style="width: 20%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->items)
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ $invoice->formatCurrency($item['rate']) }}</td>
                        <td class="text-right">{{ $invoice->formatCurrency($item['amount']) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">{{ $invoice->formatCurrency($invoice->subtotal) }}</td>
                </tr>
                @if($invoice->tax_percentage > 0)
                <tr>
                    <td class="label">Pajak ({{ $invoice->tax_percentage }}%):</td>
                    <td class="amount">{{ $invoice->formatCurrency($invoice->tax_amount) }}</td>
                </tr>
                @endif
                @if($invoice->discount_amount > 0)
                <tr>
                    <td class="label">Diskon:</td>
                    <td class="amount">- {{ $invoice->formatCurrency($invoice->discount_amount) }}</td>
                </tr>
                @endif
                @if($invoice->shipping_amount > 0)
                <tr>
                    <td class="label">Ongkir:</td>
                    <td class="amount">{{ $invoice->formatCurrency($invoice->shipping_amount) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="label">TOTAL:</td>
                    <td class="amount">{{ $invoice->formatCurrency($invoice->total) }}</td>
                </tr>
                @if($invoice->amount_paid > 0)
                <tr>
                    <td class="label">Dibayar:</td>
                    <td class="amount">- {{ $invoice->formatCurrency($invoice->amount_paid) }}</td>
                </tr>
                <tr class="balance-row">
                    <td class="label">SISA:</td>
                    <td class="amount">{{ $invoice->formatCurrency($invoice->balance_due) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes-section">
            <h4>Catatan:</h4>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Terms -->
        @if($invoice->terms)
        <div class="notes-section">
            <h4>Syarat & Ketentuan:</h4>
            <p>{{ $invoice->terms }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Invoice ini dibuat secara otomatis oleh sistem</p>
            <p>Terima kasih atas kepercayaan Anda</p>
        </div>
    </div>
</body>
</html>
