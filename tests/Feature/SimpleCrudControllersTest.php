<?php

namespace Tests\Feature;

use App\Models\Kalender;
use App\Models\Tautan;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Karakterisasi perilaku CRUD sederhana (Fase 7.3). Dipakai sebagai jaring pengaman saat
 * TautanController dan KalenderController dipindah ke SimpleCrudController: kontrak endpoint,
 * redirect, dan efek ke database tidak boleh berubah.
 */
class SimpleCrudControllersTest extends TestCase
{
    /** @return array<string, array{0:string,1:class-string,2:array,3:array,4:array}> */
    public static function modules(): array
    {
        return [
            'tautan' => ['tautan', Tautan::class,
                ['title' => 'Tautan Uji', 'url' => 'https://uji.test'],
                ['title' => 'Tautan Diubah', 'url' => 'https://ubah.test'],
                ['title' => ''],
            ],
            'kalender' => ['kalender', Kalender::class,
                ['title' => 'Acara Uji', 'start' => '2026-03-01', 'end' => '2026-03-02'],
                ['title' => 'Acara Diubah', 'start' => '2026-04-01', 'end' => '2026-04-05'],
                ['title' => '', 'start' => 'bukan-tanggal', 'end' => '2026-01-01'],
            ],
        ];
    }

    private function url(string $m, string $suffix = ''): string
    {
        return "/patra/{$m}{$suffix}";
    }

    #[DataProvider('modules')]
    public function test_halaman_index_dan_create_terbuka(string $m, string $model, array $valid): void
    {
        $this->actingAs($this->superAdmin());

        $this->get($this->url($m))->assertOk();
        $this->get($this->url($m, '/create'))->assertOk();
    }

    #[DataProvider('modules')]
    public function test_pencarian_menyaring_berdasarkan_judul(string $m, string $model, array $valid): void
    {
        $this->actingAs($this->superAdmin());
        $model::create(array_merge($valid, ['title' => 'Alpha Unik']));
        $model::create(array_merge($valid, ['title' => 'Beta Lain']));

        $this->get($this->url($m, '?search=Alpha'))->assertOk()->assertSee('Alpha Unik')->assertDontSee('Beta Lain');
    }

    #[DataProvider('modules')]
    public function test_store_valid_menyimpan_dan_kembali_ke_index(string $m, string $model, array $valid): void
    {
        $this->actingAs($this->superAdmin());

        $this->post($this->url($m), $valid)->assertRedirect(route("patra.{$m}.index"));
        $this->assertDatabaseHas((new $model)->getTable(), $valid);
    }

    #[DataProvider('modules')]
    public function test_store_tidak_valid_ditolak_dan_kembali_ke_create(string $m, string $model, array $valid, array $changed, array $invalid): void
    {
        $this->actingAs($this->superAdmin());

        $this->post($this->url($m), array_merge($valid, $invalid))->assertRedirect(route("patra.{$m}.create"));
        $this->assertSame(0, $model::count());
    }

    #[DataProvider('modules')]
    public function test_show_dan_edit_menampilkan_data(string $m, string $model, array $valid): void
    {
        $this->actingAs($this->superAdmin());
        $row = $model::create($valid);

        $this->get($this->url($m, "/{$row->id}/edit"))->assertOk()->assertSee($valid['title']);
        $this->get($this->url($m, "/{$row->id}"))->assertStatus(200);
    }

    #[DataProvider('modules')]
    public function test_show_dan_edit_data_tidak_ada_kembali_ke_index(string $m): void
    {
        $this->actingAs($this->superAdmin());

        $this->get($this->url($m, '/99999/edit'))->assertRedirect(route("patra.{$m}.index"));
        $this->get($this->url($m, '/99999'))->assertRedirect(route("patra.{$m}.index"));
    }

    #[DataProvider('modules')]
    public function test_update_mengubah_data(string $m, string $model, array $valid, array $changed): void
    {
        $this->actingAs($this->superAdmin());
        $row = $model::create($valid);

        $this->put($this->url($m, "/{$row->id}"), $changed)->assertRedirect(route("patra.{$m}.index"));
        $this->assertDatabaseHas((new $model)->getTable(), array_merge(['id' => $row->id], $changed));
    }

    #[DataProvider('modules')]
    public function test_update_data_tidak_ada_kembali_ke_edit_tanpa_error_500(string $m, string $model, array $valid, array $changed): void
    {
        $this->actingAs($this->superAdmin());

        $this->put($this->url($m, '/99999'), $changed)->assertRedirect(route("patra.{$m}.edit", 99999));
    }

    #[DataProvider('modules')]
    public function test_destroy_menghapus_dan_kembali_ke_index(string $m, string $model, array $valid): void
    {
        $this->actingAs($this->superAdmin());
        $row = $model::create($valid);

        $this->delete($this->url($m, "/{$row->id}"))->assertRedirect(route("patra.{$m}.index"));
        $this->assertNull($model::find($row->id));
    }

    #[DataProvider('modules')]
    public function test_destroy_data_tidak_ada_kembali_ke_index_tanpa_error_500(string $m): void
    {
        $this->actingAs($this->superAdmin());

        $this->delete($this->url($m, '/99999'))->assertRedirect(route("patra.{$m}.index"));
    }

    #[DataProvider('modules')]
    public function test_moderator_tidak_boleh_mengakses(string $m): void
    {
        $this->actingAs($this->moderator());

        $this->assertContains($this->get($this->url($m))->getStatusCode(), [302, 403]);
    }
}
