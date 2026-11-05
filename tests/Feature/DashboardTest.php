<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Kontak;
use App\Models\Portofolio;
use App\Services\DashboardService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Dashboard /patra: data per role, parameter periode, dan keadaan kosong. */
class DashboardTest extends TestCase
{
    private function addView(string $type, int $id, $when): void
    {
        DB::table('views')->insert([
            'viewable_type' => $type,
            'viewable_id' => $id,
            'visitor' => 'visitor-' . $id,
            'collection' => null,
            'viewed_at' => $when,
        ]);
    }

    public function test_dashboard_kosong_tetap_terbuka_untuk_semua_role(): void
    {
        foreach ([$this->superAdmin(), $this->admin(), $this->moderator()] as $user) {
            $this->actingAs($user)->get('/patra')
                ->assertOk()
                ->assertSee('Tren aktivitas')
                ->assertSee('Kesiapan website');
        }
    }

    public function test_superadmin_melihat_blok_keuangan_dan_aktivitas(): void
    {
        $this->actingAs($this->superAdmin())->get('/patra')
            ->assertOk()
            ->assertSee('Arus kas 6 bulan')
            ->assertSee('Aktivitas admin terbaru')
            ->assertSee('Invoice terbaru')
            ->assertSee('Pesan terbaru');
    }

    public function test_admin_tidak_melihat_keuangan_dan_aktivitas(): void
    {
        $this->actingAs($this->admin())->get('/patra')
            ->assertOk()
            ->assertSee('Invoice terbaru')
            ->assertDontSee('Arus kas 6 bulan')
            ->assertDontSee('Aktivitas admin terbaru')
            ->assertDontSee('patra/finance');
    }

    public function test_moderator_tidak_melihat_tautan_modul_admin(): void
    {
        $html = $this->actingAs($this->moderator())->get('/patra')->assertOk()->getContent();

        foreach (['patra/inovasi', 'patra/client-logo', 'patra/calculator-service', 'patra/finance', 'patra/invoice', 'patra/kontak', 'patra/users', 'patra/pengaturan'] as $path) {
            $this->assertStringNotContainsString($path, $html, "Moderator tidak boleh melihat {$path} di dashboard");
        }
        foreach (['patra/portofolio', 'patra/blog'] as $path) {
            $this->assertStringContainsString($path, $html);
        }
    }

    public function test_parameter_range_divalidasi(): void
    {
        $this->assertSame(30, DashboardService::normalizeRange(null));
        $this->assertSame(30, DashboardService::normalizeRange('abc'));
        $this->assertSame(30, DashboardService::normalizeRange(999));
        $this->assertSame(7, DashboardService::normalizeRange('7'));
        $this->assertSame(90, DashboardService::normalizeRange(90));

        $sa = $this->superAdmin();

        $this->actingAs($sa)->get('/patra?range=7')->assertOk()->assertSee('7 hari');
        $this->actingAs($sa)->get('/patra?range=abc')->assertOk()->assertSee('30 hari');
    }

    public function test_data_tampilan_dan_pesan_dihitung_per_periode(): void
    {
        $sa = $this->superAdmin();

        $this->addView(Portofolio::class, 999, now()->subDay());   // konten sudah dihapus: tidak boleh membuat error
        $this->addView(Portofolio::class, 999, now());
        $this->addView(Portofolio::class, 999, now()->subDays(40)); // di luar periode 30 hari

        Kontak::create(['name' => 'Budi', 'email' => 'budi@example.com', 'subject' => 'Halo', 'message' => 'Tes pesan']);

        $data = (new DashboardService($sa))->build(30);

        $this->assertCount(30, $data['traffic']['series']);
        $this->assertSame(2, $data['traffic']['total']);
        $this->assertSame(1, $data['traffic']['previous']);
        $this->assertSame([], $data['topContent'], 'Konten yang sudah dihapus harus dilewati');
        $this->assertSame(1, $data['messages']['total']);
        $this->assertSame('Budi', $data['messages']['latest'][0]['name']);

        $this->actingAs($sa)->get('/patra')->assertOk()->assertSee('Budi');
    }

    public function test_tagihan_belum_lunas_dan_jatuh_tempo_dihitung(): void
    {
        $sa = $this->superAdmin();
        $this->actingAs($sa);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-202611-0001',
            'invoice_date' => '2026-09-01',
            'due_date' => '2026-10-01',
            'bill_to_name' => 'Klien Uji',
            'currency' => 'IDR',
            'items' => [['quantity' => 2, 'rate' => 500000]],
        ]);
        $invoice->calculateTotals();
        $invoice->save();

        $data = (new DashboardService($sa))->build(30);

        $this->assertSame(1, $data['invoices']['count']);
        $this->assertSame(1, $data['invoices']['overdue']);
        $this->assertSame('Rp 1.000.000', $data['invoices']['outstanding'][0]['formatted']);
        $this->assertSame('Jatuh tempo', $data['invoices']['latest'][0]['status']);
    }

    public function test_moderator_tidak_menerima_data_admin_dari_service(): void
    {
        $data = (new DashboardService($this->moderator()))->build(30);

        $this->assertNull($data['messages']);
        $this->assertNull($data['invoices']);
        $this->assertNull($data['finance']);
        $this->assertNull($data['activity']);
        $this->assertSame(['portofolio', 'blog', 'faq', 'testimoni'], array_keys($data['modules']));
    }
}
