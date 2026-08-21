@extends('patra.layouts.master')
@section("title","Edit Invoice ~ EMPATRA DIGITECH")
@section("title_breadcumb","Invoice")
@section('breadcumb', 'Invoice')
@section('breadcumb_child', 'Edit')

@section('css')
<style>
    .invoice-preview {
        background: white;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e0e0e0;
    }

    .company-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .logo-container {
        width: 80px;
        height: 80px;
    }

    .logo-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .invoice-title h1 {
        font-size: 32px;
        color: #1a237e;
        margin-bottom: 10px;
    }

    .invoice-parties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    .party-section label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .party-section textarea,
    .party-section input {
        width: 100%;
        border: 1px solid #e0e0e0;
        padding: 10px;
        font-size: 14px;
        border-radius: 4px;
    }

    .invoice-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 40px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }

    .detail-row label {
        color: #666;
        font-size: 14px;
    }

    .detail-row input, .detail-row select {
        border: 1px solid #e0e0e0;
        padding: 8px;
        border-radius: 4px;
        width: 200px;
    }

    .items-table {
        width: 100%;
        margin-bottom: 30px;
        border-collapse: collapse;
    }

    .items-table thead {
        background: #1a237e;
        color: white;
    }

    .items-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-table td {
        padding: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .items-table input {
        width: 100%;
        border: 1px solid #e0e0e0;
        padding: 8px;
        font-size: 14px;
        border-radius: 4px;
    }

    .totals-section {
        margin-left: auto;
        width: 400px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .total-row.grand-total {
        border-top: 2px solid #333;
        padding-top: 12px;
        margin-top: 8px;
        font-weight: bold;
        font-size: 16px;
        color: #1a237e;
    }

    .total-row input {
        border: 1px solid #e0e0e0;
        padding: 6px;
        border-radius: 4px;
        width: 150px;
        text-align: right;
    }

    .btn-add-item {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    .btn-add-item:hover {
        background: #45a049;
    }

    .btn-remove-item {
        background: #f44336;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .notes-section {
        margin-top: 30px;
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('patra.invoice.update', $invoice->id) }}" method="POST" id="invoiceForm">
                        @csrf
                        @method('PUT')

                        <div class="invoice-preview">
                            <div class="invoice-header">
                                <div class="company-info">
                                    <div class="logo-container">
                                        <img src="{{ asset('images/logo.png') }}" alt="Logo">
                                    </div>
                                    <div>
                                        <input type="text" name="from_name" class="form-control mb-2"
                                               placeholder="Nama Perusahaan" required
                                               value="{{ old('from_name', $invoice->from_name) }}"
                                               style="font-size: 18px; font-weight: bold; border: 1px solid #ddd;">
                                        <textarea name="from_address" class="form-control" rows="3"
                                                  placeholder="Alamat Lengkap&#10;Kota, Provinsi&#10;Telepon"
                                                  style="font-size: 12px;">{{ old('from_address', $invoice->from_address) }}</textarea>
                                    </div>
                                </div>
                                <div class="invoice-title">
                                    <h1>INVOICE</h1>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="color: #666; font-size: 16px;">#</span>
                                        <input type="text" name="invoice_number" class="form-control"
                                               value="{{ old('invoice_number', $invoice->invoice_number) }}" required
                                               style="width: 200px; border: 1px solid #ddd;">
                                    </div>
                                </div>
                            </div>

                            <div class="invoice-parties">
                                <div>
                                    <div class="party-section mb-3">
                                        <label>Tagih Ke:</label>
                                        <input type="text" name="bill_to_name" class="form-control mb-2"
                                               placeholder="Nama Klien" required
                                               value="{{ old('bill_to_name', $invoice->bill_to_name) }}">
                                        <textarea name="bill_to_address" class="form-control" rows="3"
                                                  placeholder="Alamat Klien">{{ old('bill_to_address', $invoice->bill_to_address) }}</textarea>
                                    </div>

                                    @if($invoice->ship_to_name || old('ship_to_name'))
                                    <div class="party-section">
                                        <label>Kirim Ke:</label>
                                        <input type="text" name="ship_to_name" class="form-control mb-2"
                                               placeholder="Nama Penerima"
                                               value="{{ old('ship_to_name', $invoice->ship_to_name) }}">
                                        <textarea name="ship_to_address" class="form-control" rows="3"
                                                  placeholder="Alamat Pengiriman">{{ old('ship_to_address', $invoice->ship_to_address) }}</textarea>
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    <table style="width: 100%; font-size: 14px;">
                                        <tr>
                                            <td style="padding: 8px 0; color: #666;">Tanggal Invoice:</td>
                                            <td style="text-align: right; padding: 8px 0;">
                                                <input type="date" name="invoice_date" class="form-control"
                                                       value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #666;">Syarat Pembayaran:</td>
                                            <td style="text-align: right; padding: 8px 0;">
                                                <input type="text" name="payment_terms" class="form-control"
                                                       placeholder="Net 30"
                                                       value="{{ old('payment_terms', $invoice->payment_terms) }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #666;">Jatuh Tempo:</td>
                                            <td style="text-align: right; padding: 8px 0;">
                                                <input type="date" name="due_date" class="form-control"
                                                       value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d')) }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #666;">No. PO:</td>
                                            <td style="text-align: right; padding: 8px 0;">
                                                <input type="text" name="po_number" class="form-control"
                                                       placeholder="Nomor Purchase Order"
                                                       value="{{ old('po_number', $invoice->po_number) }}">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <table class="items-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">DESKRIPSI</th>
                                        <th class="text-center" style="width: 15%;">JUMLAH</th>
                                        <th class="text-right" style="width: 15%;">HARGA</th>
                                        <th class="text-right" style="width: 15%;">TOTAL</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    @php
                                        $items = old('items') ? json_decode(old('items'), true) : ($invoice->items ?? []);
                                    @endphp

                                    @if(!empty($items))
                                        @foreach($items as $item)
                                            <tr class="item-row">
                                                <td>
                                                    <input type="text" class="form-control item-desc"
                                                           placeholder="Deskripsi item/layanan" required
                                                           value="{{ $item['description'] ?? '' }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control item-qty text-center"
                                                           value="{{ $item['quantity'] ?? 1 }}" min="1" step="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control item-rate text-right"
                                                           value="{{ $item['rate'] ?? 0 }}" min="0" step="0.01" required>
                                                </td>
                                                <td class="text-right item-amount">Rp 0</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn-remove-item" onclick="removeItem(this)">×</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="item-row">
                                            <td>
                                                <input type="text" class="form-control item-desc"
                                                       placeholder="Deskripsi item/layanan" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-qty text-center"
                                                       value="1" min="1" step="1" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-rate text-right"
                                                       value="0" min="0" step="0.01" required>
                                            </td>
                                            <td class="text-right item-amount">Rp 0</td>
                                            <td class="text-center">
                                                <button type="button" class="btn-remove-item" onclick="removeItem(this)">×</button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <button type="button" class="btn-add-item" onclick="addLineItem()">
                                + Tambah Item
                            </button>

                            <div class="totals-section">
                                <table style="width: 100%;">
                                    <tr>
                                        <td class="total-row" style="color: #666;">Subtotal:</td>
                                        <td class="total-row" style="text-align: right; font-weight: bold;" id="subtotal">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="total-row" style="color: #666;">Pajak (%):</td>
                                        <td class="total-row" style="text-align: right;">
                                            <input type="number" name="tax_percentage" id="taxPercent"
                                                   value="{{ old('tax_percentage', $invoice->tax_percentage ?? 0) }}"
                                                   step="0.01" min="0" max="100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="total-row" style="color: #666;">Diskon:</td>
                                        <td class="total-row" style="text-align: right;">
                                            <input type="number" name="discount_amount" id="discount"
                                                   value="{{ old('discount_amount', $invoice->discount_amount ?? 0) }}"
                                                   step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="total-row" style="color: #666;">Ongkir:</td>
                                        <td class="total-row" style="text-align: right;">
                                            <input type="number" name="shipping_amount" id="shipping"
                                                   value="{{ old('shipping_amount', $invoice->shipping_amount ?? 0) }}"
                                                   step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr class="grand-total">
                                        <td>TOTAL:</td>
                                        <td style="text-align: right;" id="total">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td class="total-row" style="color: #666;">Dibayar:</td>
                                        <td class="total-row" style="text-align: right;">
                                            <input type="number" name="amount_paid" id="amountPaid"
                                                   value="{{ old('amount_paid', $invoice->amount_paid ?? 0) }}"
                                                   step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr class="grand-total" style="background-color: #f5f5f5;">
                                        <td style="color: #d32f2f;">SISA:</td>
                                        <td style="text-align: right; color: #d32f2f;" id="balanceDue">Rp 0</td>
                                    </tr>
                                </table>
                            </div>

                            @if($invoice->notes || old('notes'))
                            <div class="notes-section">
                                <h4>Catatan:</h4>
                                <textarea name="notes" class="form-control" rows="3"
                                          placeholder="Catatan tambahan jika diperlukan">{{ old('notes', $invoice->notes) }}</textarea>
                            </div>
                            @endif

                            @if($invoice->terms || old('terms'))
                            <div class="notes-section">
                                <h4>Syarat & Ketentuan:</h4>
                                <textarea name="terms" class="form-control" rows="3"
                                          placeholder="Syarat pembayaran, denda keterlambatan, dll">{{ old('terms', $invoice->terms) }}</textarea>
                            </div>
                            @endif

                            <input type="hidden" name="items" id="itemsData">
                            <input type="hidden" name="currency" value="{{ old('currency', $invoice->currency ?? 'IDR') }}">
                            <input type="hidden" name="theme" value="{{ old('theme', $invoice->theme ?? 'classic') }}">
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="{{ route('patra.invoice.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Invoice
                                </button>
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
    function addLineItem() {
        const tbody = document.getElementById('itemsBody');
        const row = tbody.insertRow();
        row.className = 'item-row';
        row.innerHTML = `
            <td>
                <input type="text" class="form-control item-desc"
                       placeholder="Deskripsi item/layanan" required>
            </td>
            <td>
                <input type="number" class="form-control item-qty text-center"
                       value="1" min="1" step="1" required>
            </td>
            <td>
                <input type="number" class="form-control item-rate text-right"
                       value="0" min="0" step="0.01" required>
            </td>
            <td class="text-right item-amount">Rp 0</td>
            <td class="text-center">
                <button type="button" class="btn-remove-item" onclick="removeItem(this)">×</button>
            </td>
        `;

        row.querySelector('.item-qty').addEventListener('input', calculateTotals);
        row.querySelector('.item-rate').addEventListener('input', calculateTotals);

        calculateTotals();
    }

    function removeItem(btn) {
        const row = btn.closest('tr');
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            calculateTotals();
        } else {
            alert('Minimal harus ada 1 item!');
        }
    }

    function calculateTotals() {
        const rows = document.querySelectorAll('.item-row');
        let subtotal = 0;

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
            const amount = qty * rate;
            row.querySelector('.item-amount').textContent = formatCurrency(amount);
            subtotal += amount;
        });

        const taxPercent = parseFloat(document.getElementById('taxPercent').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const shipping = parseFloat(document.getElementById('shipping').value) || 0;
        const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;

        const taxAmount = subtotal * (taxPercent / 100);
        const total = subtotal + taxAmount - discount + shipping;
        const balanceDue = total - amountPaid;

        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('total').textContent = formatCurrency(total);
        document.getElementById('balanceDue').textContent = formatCurrency(balanceDue);
    }

    function formatCurrency(amount) {
        return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
    }

    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const rows = document.querySelectorAll('.item-row');
        const items = [];

        rows.forEach(row => {
            const desc = row.querySelector('.item-desc').value;
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const rate = parseFloat(row.querySelector('.item-rate').value) || 0;

            items.push({
                description: desc,
                quantity: qty,
                rate: rate,
                amount: qty * rate
            });
        });

        document.getElementById('itemsData').value = JSON.stringify(items);
        this.submit();
    });

    // Event listeners
    document.getElementById('taxPercent').addEventListener('input', calculateTotals);
    document.getElementById('discount').addEventListener('input', calculateTotals);
    document.getElementById('shipping').addEventListener('input', calculateTotals);
    document.getElementById('amountPaid').addEventListener('input', calculateTotals);

    document.querySelectorAll('.item-qty, .item-rate').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // Hitung saat load
    calculateTotals();
</script>
@endsection
