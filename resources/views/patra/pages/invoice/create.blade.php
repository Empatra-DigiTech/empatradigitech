@extends('patra.layouts.master')
@section("title","Buat Invoice ~ EMPATRA DIGITECH")
@section("title_breadcumb","Invoice")
@section('breadcumb', 'Invoice')
@section('breadcumb_child', 'Create')

@php
    $company = App\Models\Pengaturan::first();
@endphp


@section('css')
    @vite('resources/css/patra/invoice-create.css')
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
                            <div class="invoice-header">
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

                            <!-- Info Perusahaan (Read-only, dari Pengaturan) -->
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
                                           placeholder="Nama Klien" required>
                                    <textarea name="bill_to_address" class="form-control" rows="3"
                                              placeholder="Alamat Klien"></textarea>
                                </div>

                                <div class="party-section">
                                    <label>Kirim Ke (Ship To) - Opsional</label>
                                    <input type="text" name="ship_to_name" class="form-control mb-2"
                                           placeholder="Nama Penerima">
                                    <textarea name="ship_to_address" class="form-control" rows="3"
                                              placeholder="Alamat Pengiriman"></textarea>
                                </div>
                            </div>

                            <!-- Rest of form tetap sama -->
                            <div class="invoice-details">
                                <div>
                                    <div class="detail-row">
                                        <label>Tanggal Invoice</label>
                                        <input type="date" name="invoice_date" class="form-control"
                                               value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="detail-row">
                                        <label>Syarat Pembayaran</label>
                                        <input type="text" name="payment_terms" class="form-control"
                                               placeholder="Net 30">
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <label>Jatuh Tempo</label>
                                        <input type="date" name="due_date" class="form-control">
                                    </div>
                                    <div class="detail-row">
                                        <label>No. PO</label>
                                        <input type="text" name="po_number" class="form-control"
                                               placeholder="Nomor Purchase Order">
                                    </div>
                                </div>
                            </div>

                            <div class="detail-row mb-3">
                                <label>Mata Uang</label>
                                <select name="currency" id="currency" class="form-control" style="width: 200px;">
                                    <option value="IDR" selected>IDR (Rp)</option>
                                    <option value="USD">USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                    <option value="GBP">GBP (£)</option>
                                </select>
                            </div>

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

        // Batal -> berhenti di sini; form hanya dikirim bila pengguna menyetujui.
        if (!confirm('Apakah anda yakin ingin mengirim data ini?')) {
            return;
        }

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
