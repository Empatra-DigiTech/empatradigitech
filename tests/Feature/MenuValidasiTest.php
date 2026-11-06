<?php

namespace Tests\Feature;

use App\Models\Menu;
use Tests\TestCase;

/** C5: validasi parent menu, batas judul, hapus menu berinduk, dan halaman show. */
class MenuValidasiTest extends TestCase
{
    private $saCache;

    private function sa()
    {
        return $this->saCache ??= $this->superAdmin();
    }

    private function menu(string $title, ?int $parent = null): Menu
    {
        return Menu::create(['title' => $title, 'parent' => $parent]);
    }

    private function simpan(array $o = []): void
    {
        $this->actingAs($this->sa())->post('/patra/menu', array_merge(['title' => 'Baru'], $o));
    }

    public function test_parent_harus_menu_yang_ada(): void
    {
        $this->simpan(['parent' => 99999]);

        $this->assertDatabaseMissing('menus', ['title' => 'Baru']);
    }

    public function test_parent_harus_menu_level_teratas(): void
    {
        $a = $this->menu('A');
        $b = $this->menu('B', $a->id);

        $this->simpan(['parent' => $b->id]);

        $this->assertDatabaseMissing('menus', ['title' => 'Baru']);
    }

    public function test_sub_menu_valid_tersimpan(): void
    {
        $a = $this->menu('A');

        $this->simpan(['parent' => $a->id]);

        $this->assertDatabaseHas('menus', ['title' => 'Baru', 'parent' => $a->id]);
    }

    public function test_menu_tidak_boleh_jadi_induk_dirinya_sendiri(): void
    {
        $a = $this->menu('A');

        $this->actingAs($this->sa())->put("/patra/menu/{$a->id}", ['title' => 'A2', 'parent' => $a->id]);

        $this->assertDatabaseHas('menus', ['id' => $a->id, 'title' => 'A', 'parent' => null]);
    }

    public function test_menu_yang_punya_sub_tidak_boleh_menjadi_sub(): void
    {
        $a = $this->menu('A');
        $this->menu('A1', $a->id);
        $b = $this->menu('B');

        $this->actingAs($this->sa())->put("/patra/menu/{$a->id}", ['title' => 'A', 'parent' => $b->id]);

        $this->assertDatabaseHas('menus', ['id' => $a->id, 'parent' => null]);
    }

    public function test_judul_maks_16_saat_simpan_dan_saat_diubah(): void
    {
        $this->simpan(['title' => str_repeat('a', 17)]);
        $this->assertDatabaseMissing('menus', ['title' => str_repeat('a', 17)]);

        $a = $this->menu('A');
        $this->actingAs($this->sa())->put("/patra/menu/{$a->id}", ['title' => str_repeat('b', 17)]);
        $this->assertDatabaseHas('menus', ['id' => $a->id, 'title' => 'A']);
    }

    public function test_menu_lama_berjudul_panjang_tetap_bisa_diedit_tanpa_ganti_judul(): void
    {
        $panjang = str_repeat('x', 30);
        $a = $this->menu($panjang);

        $this->actingAs($this->sa())->put("/patra/menu/{$a->id}", ['title' => $panjang]);

        $this->assertStringNotContainsString('Menu tidak lebih', (string) session('alert.config'));
    }

    public function test_hapus_menu_yang_punya_sub_ditolak(): void
    {
        $a = $this->menu('A');
        $this->menu('A1', $a->id);

        $this->actingAs($this->sa())->delete("/patra/menu/{$a->id}");

        $this->assertDatabaseHas('menus', ['id' => $a->id]);
        $this->assertStringContainsString('sub menu', (string) session('alert.config'));
    }

    public function test_hapus_menu_tanpa_sub_dan_menu_yang_tidak_ada(): void
    {
        $a = $this->menu('A');
        $sa = $this->sa();

        $this->actingAs($sa)->delete("/patra/menu/{$a->id}");
        $this->assertDatabaseMissing('menus', ['id' => $a->id]);

        $this->actingAs($sa)->delete('/patra/menu/99999')->assertRedirect(route('patra.menu.index'));
        $this->assertStringContainsString('tidak ditemukan', (string) session('alert.config'));
    }

    public function test_show_menu_tidak_ada_kembali_ke_daftar_bukan_error(): void
    {
        $this->actingAs($this->sa())->get('/patra/menu/99999')
            ->assertRedirect(route('patra.menu.index'));
    }
}
