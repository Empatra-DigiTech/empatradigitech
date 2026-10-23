<?php

namespace Tests\Feature;

use App\Models\Invoice;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    private function payload(array $over = []): array
    {
        return array_merge([
            'invoice_number' => 'INV-TEST-001',
            'bill_to_name' => 'PT Klien',
            'bill_to_address' => 'Jl. Contoh 1',
            'invoice_date' => '2026-10-01',
            'due_date' => '2026-10-15',
            'items' => json_encode([
                ['description' => 'Website', 'quantity' => 2, 'rate' => 500000, 'amount' => 1000000],
                ['description' => 'Hosting', 'quantity' => 1, 'rate' => 100000, 'amount' => 100000],
            ]),
            'tax_percentage' => 10,
            'discount_amount' => 50000,
            'shipping_amount' => 0,
            'amount_paid' => 200000,
        ], $over);
    }

    public function test_hitung_total_benar(): void
    {
        $this->actingAs($this->admin())->post('/patra/invoice', $this->payload())->assertRedirect();

        $inv = Invoice::where('invoice_number', 'INV-TEST-001')->firstOrFail();
        // subtotal 1.100.000 ; pajak 10% = 110.000 ; diskon 50.000 -> total 1.160.000
        $this->assertEquals(1100000, $inv->subtotal);
        $this->assertEquals(110000, $inv->tax_amount);
        $this->assertEquals(1160000, $inv->total);
        $this->assertEquals(960000, $inv->balance_due);
    }

    public function test_halaman_buat_dan_edit_tampil(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload());
        $inv = Invoice::firstOrFail();

        $this->actingAs($admin)->get('/patra/invoice/create')->assertOk();
        $this->actingAs($admin)->get("/patra/invoice/{$inv->id}/edit")->assertOk();
        $this->actingAs($admin)->get("/patra/invoice/{$inv->id}")->assertOk();
    }

    public function test_download_pdf_berjalan(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload());
        $inv = Invoice::firstOrFail();

        $this->actingAs($admin)->get("/patra/invoice/{$inv->id}/download")->assertOk();
    }

    public function test_update_invoice_menghitung_ulang(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload());
        $inv = Invoice::firstOrFail();

        $this->actingAs($admin)->put("/patra/invoice/{$inv->id}", $this->payload([
            'items' => json_encode([['description' => 'X', 'quantity' => 1, 'rate' => 1000, 'amount' => 1000]]),
            'tax_percentage' => 0, 'discount_amount' => 0, 'amount_paid' => 0,
        ]))->assertRedirect();

        $this->assertEquals(1000, $inv->fresh()->total);
    }

    public function test_moderator_tidak_boleh_akses_invoice(): void
    {
        $this->actingAs($this->moderator())->get('/patra/invoice')->assertForbidden();
    }

    // ---- Perbaikan Fase 2.3 ----

    public function test_item_tanpa_kolom_amount_tetap_bisa_ditampilkan(): void
    {
        // Server menghitung amount = quantity * rate sendiri (tidak percaya kiriman browser).
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload([
            'items' => json_encode([['description' => 'A', 'quantity' => 2, 'rate' => 1000]]),
        ]));
        $inv = Invoice::firstOrFail();

        $this->actingAs($admin)->get("/patra/invoice/{$inv->id}")->assertOk();
        $this->actingAs($admin)->get("/patra/invoice/{$inv->id}/download")->assertOk();
    }

    public function test_json_item_rusak_ditolak_dengan_validasi(): void
    {

        $this->actingAs($this->admin())
            ->post('/patra/invoice', $this->payload(['items' => '{rusak']))
            ->assertRedirect();

        $this->assertSame(0, Invoice::count());
    }

    public function test_item_tanpa_nama_atau_angka_negatif_ditolak(): void
    {

        $this->actingAs($this->admin())->post('/patra/invoice', $this->payload([
            'items' => json_encode([['description' => '', 'quantity' => -1, 'rate' => 'abc']]),
        ]));

        $this->assertSame(0, Invoice::count());
    }

    public function test_field_wajib_invoice_divalidasi(): void
    {

        $this->actingAs($this->admin())->post('/patra/invoice', ['items' => '[]']);

        $this->assertSame(0, Invoice::count());
    }

    public function test_amount_palsu_dari_browser_diabaikan(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload([
            'items' => json_encode([['description' => 'A', 'quantity' => 2, 'rate' => 1000, 'amount' => 1]]),
            'tax_percentage' => 0, 'discount_amount' => 0, 'amount_paid' => 0,
        ]));

        $inv = Invoice::firstOrFail();
        $this->assertEquals(2000, $inv->items[0]['amount']);
        $this->assertEquals(2000, $inv->total);
    }

    public function test_nomor_invoice_ganda_ditolak(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload());
        $this->actingAs($admin)->post('/patra/invoice', $this->payload());

        $this->assertSame(1, Invoice::count());
    }

    public function test_pajak_di_atas_100_persen_ditolak(): void
    {
        $this->actingAs($this->admin())->post('/patra/invoice', $this->payload(['tax_percentage' => 150]));

        $this->assertSame(0, Invoice::count());
    }

    public function test_update_boleh_mempertahankan_nomor_sendiri(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/patra/invoice', $this->payload());
        $inv = Invoice::firstOrFail();

        $this->actingAs($admin)->put("/patra/invoice/{$inv->id}", $this->payload(['bill_to_name' => 'Baru']));

        $this->assertSame('Baru', $inv->fresh()->bill_to_name);
    }
}
