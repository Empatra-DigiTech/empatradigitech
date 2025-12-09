@extends('patra.layouts.master')
@section("title","Edit Invoice ~ EMPATRA DIGITECH")
@section("title_breadcumb","Invoice")
@section('breadcumb', 'Invoice')
@section('breadcumb_child', 'Edit')

@php
    $company = App\Models\Pengaturan::first();
@endphp

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

    .logo-section {
        cursor: pointer;
        padding: 40px;
        border: 2px dashed #ccc;
        text-align: center;
        color: #999;
        border-radius: 4px;
    }

    .invoice-title h1 {
        font-size: 32px;
        color: #333;
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

    .detail-row input {
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
        font-size: 12px;
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- pastikan route dan method update sudah ada --}}
                    <form action="{{ route('patra.invoice.update', $invoice->id) }}" method="POST" id="invoiceForm">
                        @csrf
                        @method('PUT')

                        <div class="invoice-preview">
                            <div class="invoice-header">
                                <div class="invoice-title">
                                    <h1>INVOICE</h1>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="color: #666;">#</span>
                                        <input type="text" name="invoice_number" class="form-control"
                                               value="{{ old('invoice_number', $invoice->invoice_number) }}" required
                                               style="width: 200px; border: 1px solid #ddd;">
                                    </div>
                                </div>
                            </div>

                            @if($company)
                                <div class="alert alert-info mb-4">
                                    <div class="d-flex align-items-start">
                                        @if($company->website_logo)
                                            <img src="{{ asset('storage/' . $company->website_logo) }}"
                                                alt="Logo" style="max-width: 80px; max-height: 50px; margin-right: 15px;">
                                        @endif
                                        <div>
                                            <strong>{{ $company->website_name }}</strong><br>
                                            @if($company->website_address)
                                                <small style="white-space: pre-line;">{{ $company->website_address }}</small><br>
                                            @endif
                                            @if($company->website_phone)
                                                <small><i class="fa fa-phone"></i> {{ $company->website_phone }}</small>
                                            @endif
                                            @if($company->website_email)
                                                <small><i class="fa fa-envelope"></i> {{ $company->website_email }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fa fa-info-circle"></i> Informasi perusahaan diambil dari
                                        <a href="{{ route('patra.pengaturan.index') }}" target="_blank">Pengaturan</a>
                                    </small>
                                </div>
                                @endif

                                <div class="invoice-parties">
                                    <div class="party-section">
                                        <label>Tagih Ke (Bill To)</label>
                                        <input type="text" name="bill_to_name" class="form-control mb-2"
                                            placeholder="Nama Klien" required
                                            value="{{ old('bill_to_name', $invoice->bill_to_name) }}">
                                        <textarea name="bill_to_address" class="form-control" rows="3"
                                                placeholder="Alamat Klien">{{ old('bill_to_address', $invoice->bill_to_address) }}</textarea>
                                    </div>

                                    <div class="party-section">
                                        <label>Kirim Ke (Ship To) - Opsional</label>
                                        <input type="text" name="ship_to_name" class="form-control mb-2"
                                            placeholder="Nama Penerima"
                                            value="{{ old('ship_to_name', $invoice->ship_to_name) }}">
                                        <textarea name="ship_to_address" class="form-control" rows="3"
                                                placeholder="Alamat Pengiriman">{{ old('ship_to_address', $invoice->ship_to_address) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="invoice-details">
                                <div>
                                    <div class="detail-row">
                                        <label>Tanggal Invoice</label>
                                        <input type="date" name="invoice_date" class="form-control"
                                               value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}" required>
                                    </div>
                                    <div class="detail-row">
                                        <label>Syarat Pembayaran</label>
                                        <input type="text" name="payment_terms" class="form-control"
                                               placeholder="Net 30"
                                               value="{{ old('payment_terms', $invoice->payment_terms) }}">
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <label>Jatuh Tempo</label>
                                        <input type="date" name="due_date" class="form-control"
                                               value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="detail-row">
                                        <label>No. PO</label>
                                        <input type="text" name="po_number" class="form-control"
                                               placeholder="Nomor Purchase Order"
                                               value="{{ old('po_number', $invoice->po_number) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="detail-row mb-3">
                                <label>Mata Uang</label>
                                @php
                                    $selectedCurrency = old('currency', $invoice->currency ?? 'IDR');
                                @endphp
                                <select name="currency" id="currency" class="form-control" style="width: 200px;">
                                    <option value="IDR" {{ $selectedCurrency === 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                                    <option value="USD" {{ $selectedCurrency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ $selectedCurrency === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="GBP" {{ $selectedCurrency === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                </select>
                            </div>

                            <table class="items-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Item / Deskripsi</th>
                                        <th class="text-center" style="width: 10%;">Jumlah</th>
                                        <th class="text-right" style="width: 15%;">Harga</th>
                                        <th class="text-right" style="width: 15%;">Total</th>
                                        <th style="width: 10%;"></th>
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
                                                <td class="text-right item-amount">
                                                    {{-- Akan diupdate oleh JS calculateTotals --}}
                                                    {{ isset($item['amount']) ? $item['amount'] : 0 }}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn-remove-item" onclick="removeItem(this)">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        {{-- fallback kalau tidak ada items --}}
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
                                                <button type="button" class="btn-remove-item" onclick="removeItem(this)">Hapus</button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <button type="button" class="btn-add-item" onclick="addLineItem()">
                                + Tambah Item
                            </button>

                            <div class="totals-section">
                                <div class="total-row">
                                    <span>Subtotal</span>
                                    <span id="subtotal">
                                        {{-- akan diisi JS, tapi kasih default --}}
                                        {{ isset($invoice->subtotal) ? $invoice->formatCurrency($invoice->subtotal) : 'Rp 0' }}
                                    </span>
                                </div>
                                <div class="total-row">
                                    <span>Pajak (%)</span>
                                    <input type="number" name="tax_percentage" id="taxPercent"
                                           value="{{ old('tax_percentage', $invoice->tax_percentage ?? 0) }}"
                                           step="0.01" min="0" max="100">
                                </div>
                                <div class="total-row">
                                    <span>Diskon</span>
                                    <input type="number" name="discount_amount" id="discount"
                                           value="{{ old('discount_amount', $invoice->discount_amount ?? 0) }}"
                                           step="0.01" min="0">
                                </div>
                                <div class="total-row">
                                    <span>Ongkir</span>
                                    <input type="number" name="shipping_amount" id="shipping"
                                           value="{{ old('shipping_amount', $invoice->shipping_amount ?? 0) }}"
                                           step="0.01" min="0">
                                </div>
                                <div class="total-row grand-total">
                                    <span>Total</span>
                                    <span id="total">
                                        {{ isset($invoice->total) ? $invoice->formatCurrency($invoice->total) : 'Rp 0' }}
                                    </span>
                                </div>
                                <div class="total-row">
                                    <span>Dibayar</span>
                                    <input type="number" name="amount_paid" id="amountPaid"
                                           value="{{ old('amount_paid', $invoice->amount_paid ?? 0) }}"
                                           step="0.01" min="0">
                                </div>
                                <div class="total-row grand-total">
                                    <span>Sisa</span>
                                    <span id="balanceDue">
                                        {{ isset($invoice->balance_due) ? $invoice->formatCurrency($invoice->balance_due) : 'Rp 0' }}
                                    </span>
                                </div>
                            </div>

                            <div class="party-section mt-4">
                                <label>Catatan</label>
                                <textarea name="notes" class="form-control" rows="3"
                                          placeholder="Catatan tambahan jika diperlukan">{{ old('notes', $invoice->notes) }}</textarea>
                            </div>

                            <div class="party-section mt-3">
                                <label>Syarat & Ketentuan</label>
                                <textarea name="terms" class="form-control" rows="3"
                                          placeholder="Syarat pembayaran, denda keterlambatan, dll">{{ old('terms', $invoice->terms) }}</textarea>
                            </div>

                            <input type="hidden" name="items" id="itemsData">
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
                <button type="button" class="btn-remove-item" onclick="removeItem(this)">Hapus</button>
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
        const currency = document.getElementById('currency').value;
        let subtotal = 0;

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
            const amount = qty * rate;
            row.querySelector('.item-amount').textContent = formatCurrency(amount, currency);
            subtotal += amount;
        });

        const taxPercent = parseFloat(document.getElementById('taxPercent').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const shipping = parseFloat(document.getElementById('shipping').value) || 0;
        const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;

        const taxAmount = subtotal * (taxPercent / 100);
        const total = subtotal + taxAmount - discount + shipping;
        const balanceDue = total - amountPaid;

        document.getElementById('subtotal').textContent = formatCurrency(subtotal, currency);
        document.getElementById('total').textContent = formatCurrency(total, currency);
        document.getElementById('balanceDue').textContent = formatCurrency(balanceDue, currency);
    }

    function formatCurrency(amount, currency) {
        if (currency === 'IDR') {
            return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }

        const symbols = { USD: '$', EUR: '€', GBP: '£' };
        const symbol = symbols[currency] || '$';
        return symbol + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
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
    document.getElementById('currency').addEventListener('change', calculateTotals);
    document.getElementById('taxPercent').addEventListener('input', calculateTotals);
    document.getElementById('discount').addEventListener('input', calculateTotals);
    document.getElementById('shipping').addEventListener('input', calculateTotals);
    document.getElementById('amountPaid').addEventListener('input', calculateTotals);

    document.querySelectorAll('.item-qty, .item-rate').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // hitung ulang saat halaman pertama kali dibuka
    calculateTotals();
</script>
@endsection
