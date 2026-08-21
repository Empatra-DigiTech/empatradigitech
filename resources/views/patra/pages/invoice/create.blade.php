@extends('patra.layouts.master')
@section("title","Buat Invoice ~ EMPATRA DIGITECH")
@section("title_breadcumb","Invoice")
@section('breadcumb', 'Invoice')
@section('breadcumb_child', 'Create')

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

    .company-details {
        flex: 1;
    }

    .invoice-title {
        text-align: right;
    }

    .invoice-title h1 {
        font-size: 36px;
        color: #1a237e;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .invoice-number {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        font-size: 16px;
        color: #666;
    }

    .invoice-info {
        margin: 30px 0;
    }

    .invoice-parties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    .info-section h4 {
        font-size: 11px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .info-section input,
    .info-section textarea {
        width: 100%;
        border: 1px solid #e0e0e0;
        padding: 10px;
        font-size: 14px;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .info-section input:focus,
    .info-section textarea:focus {
        outline: none;
        border-color: #1a237e;
    }

    .details-table {
        width: 100%;
    }

    .details-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .details-table td {
        padding: 8px 0;
    }

    .details-table .label {
        color: #666;
        font-size: 14px;
    }

    .details-table .value {
        text-align: right;
    }

    .details-table input,
    .details-table select {
        width: 200px;
        border: 1px solid #e0e0e0;
        padding: 8px;
        border-radius: 4px;
        font-size: 14px;
    }

    .items-table {
        width: 100%;
        margin: 30px 0;
        border-collapse: collapse;
    }

    .items-table thead {
        background: #1a237e;
        color: white;
    }

    .items-table th {
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
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

    .items-table input {
        width: 100%;
        border: 1px solid #e0e0e0;
        padding: 8px;
        font-size: 14px;
        border-radius: 4px;
    }

    .btn-add-item {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
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
        font-size: 18px;
        line-height: 1;
        width: 30px;
        height: 30px;
    }

    .btn-remove-item:hover {
        background: #da190b;
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
        color: #666;
    }

    .totals-table tr td:last-child {
        text-align: right;
        font-weight: bold;
        color: #333;
    }

    .totals-table input {
        border: 1px solid #e0e0e0;
        padding: 6px 10px;
        border-radius: 4px;
        width: 150px;
        text-align: right;
    }

    .total-row {
        border-top: 2px solid #333 !important;
        padding-top: 12px;
    }

    .total-row td {
        padding-top: 15px !important;
        font-size: 16px !important;
        font-weight: bold !important;
        color: #1a237e !important;
    }

    .balance-row {
        background-color: #f5f5f5;
    }

    .balance-row td {
        padding: 10px !important;
        font-size: 18px !important;
        font-weight: bold !important;
        color: #d32f2f !important;
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

    .notes-section textarea {
        width: 100%;
        border: 1px solid #e0e0e0;
        padding: 10px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.6;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('patra.invoice.store') }}" method="POST" id="invoiceForm">
                        @csrf

                        <div class="invoice-preview">
                            <!-- Header dengan Logo -->
                            <div class="invoice-header">
                                <div class="company-info">
                                    <div class="logo-container">
                                        <img src="{{ asset('images/logo.png') }}" alt="Company Logo">
                                    </div>
                                    <div class="company-details">
                                        <input type="text" name="from_name" class="form-control mb-2"
                                               placeholder="Nama Perusahaan" required
                                               style="font-size: 18px; font-weight: bold;">
                                        <textarea name="from_address" class="form-control" rows="3"
                                                  placeholder="Alamat Lengkap&#10;Kota, Provinsi&#10;Telepon"></textarea>
                                    </div>
                                </div>
                                <div class="invoice-title">
                                    <h1>INVOICE</h1>
                                    <div class="invoice-number">
                                        <span>#</span>
                                        <input type="text" name="invoice_number" class="form-control"
                                               value="{{ $invoiceNumber }}" required
                                               style="width: 200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Info -->
                            <div class="invoice-info">
                                <div class="invoice-parties">
                                    <div>
                                        <div class="info-section">
                                            <h4>Tagih Ke:</h4>
                                            <input type="text" name="bill_to_name" class="form-control"
                                                   placeholder="Nama Klien" required>
                                            <textarea name="bill_to_address" class="form-control" rows="3"
                                                      placeholder="Alamat Klien"></textarea>
                                        </div>

                                        <div class="info-section mt-3">
                                            <h4>Kirim Ke: (Opsional)</h4>
                                            <input type="text" name="ship_to_name" class="form-control"
                                                   placeholder="Nama Penerima">
                                            <textarea name="ship_to_address" class="form-control" rows="3"
                                                      placeholder="Alamat Pengiriman"></textarea>
                                        </div>
                                    </div>

                                    <div>
                                        <table class="details-table">
                                            <tr>
                                                <td class="label">Tanggal Invoice:</td>
                                                <td class="value">
                                                    <input type="date" name="invoice_date" class="form-control"
                                                           value="{{ date('Y-m-d') }}" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">Syarat Pembayaran:</td>
                                                <td class="value">
                                                    <input type="text" name="payment_terms" class="form-control"
                                                           placeholder="Net 30">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">Jatuh Tempo:</td>
                                                <td class="value">
                                                    <input type="date" name="due_date" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">No. PO:</td>
                                                <td class="value">
                                                    <input type="text" name="po_number" class="form-control"
                                                           placeholder="Nomor Purchase Order">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Items Table -->
                            <table class="items-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">DESKRIPSI</th>
                                        <th class="text-center" style="width: 15%;">JUMLAH</th>
                                        <th class="text-right" style="width: 15%;">HARGA</th>
                                        <th class="text-right" style="width: 15%;">TOTAL</th>
                                        <th class="text-center" style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
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
                                </tbody>
                            </table>

                            <button type="button" class="btn-add-item" onclick="addLineItem()">
                                + Tambah Item
                            </button>

                            <!-- Totals -->
                            <div class="totals-section">
                                <table class="totals-table">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td id="subtotal">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Pajak (%):</td>
                                        <td>
                                            <input type="number" name="tax_percentage" id="taxPercent"
                                                   value="0" step="0.01" min="0" max="100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Diskon:</td>
                                        <td>
                                            <input type="number" name="discount_amount" id="discount"
                                                   value="0" step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ongkir:</td>
                                        <td>
                                            <input type="number" name="shipping_amount" id="shipping"
                                                   value="0" step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr class="total-row">
                                        <td>TOTAL:</td>
                                        <td id="total">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Dibayar:</td>
                                        <td>
                                            <input type="number" name="amount_paid" id="amountPaid"
                                                   value="0" step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr class="balance-row">
                                        <td>SISA:</td>
                                        <td id="balanceDue">Rp 0</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Notes -->
                            <div class="notes-section">
                                <h4>Catatan:</h4>
                                <textarea name="notes" class="form-control" rows="3"
                                          placeholder="Catatan tambahan jika diperlukan"></textarea>
                            </div>

                            <!-- Terms -->
                            <div class="notes-section">
                                <h4>Syarat & Ketentuan:</h4>
                                <textarea name="terms" class="form-control" rows="3"
                                          placeholder="Syarat pembayaran, denda keterlambatan, dll"></textarea>
                            </div>

                            <input type="hidden" name="items" id="itemsData">
                            <input type="hidden" name="currency" value="IDR">
                            <input type="hidden" name="theme" value="classic">
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="{{ route('patra.invoice.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan Invoice
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

    calculateTotals();
</script>
@endsection
