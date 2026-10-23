<?php

namespace Tests\Feature;

use App\Helpers\UploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadHelperTest extends TestCase
{
    public function test_ekstensi_diizinkan_berhasil(): void
    {
        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->image('a.png'), 'uji', ['png', 'jpg']);

        $this->assertFalse($r['IsError']);
        $this->assertStringStartsWith('uji/', $r['Path']);
    }

    public function test_ekstensi_tidak_diizinkan_ditolak(): void
    {
        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->create('a.php', 1), 'uji', ['png', 'jpg']);

        $this->assertTrue($r['IsError']);
    }

    public function test_file_terlalu_besar_ditolak(): void
    {
        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->create('a.png', 2048), 'uji', ['png'], 1024);

        $this->assertTrue($r['IsError']);
    }

    public function test_ekstensi_dicek_case_insensitive(): void
    {
        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->image('A.PNG'), 'uji', ['png']);

        $this->assertFalse($r['IsError']);
    }

    // ---- Perbaikan Fase 2.2 ----

    public function test_daftar_ekstensi_kosong_tidak_boleh_menerima_apa_saja(): void
    {

        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->create('shell.php', 1), 'uji', []);

        $this->assertTrue($r['IsError']);
    }

    public function test_isi_file_harus_cocok_dengan_ekstensi(): void
    {

        Storage::fake();
        // File sungguhan (bukan fake): UploadedFile::fake() menebak MIME dari nama, bukan isi.
        $tmp = tempnam(sys_get_temp_dir(), 'upl');
        file_put_contents($tmp, '<?php echo 1;');
        $palsu = new UploadedFile($tmp, 'palsu.png', null, null, true);
        $r = UploadHelper::upload_file($palsu, 'uji', ['png']);

        $this->assertTrue($r['IsError']);
    }

    public function test_pesan_batas_ukuran_sesuai_batas_sebenarnya(): void
    {

        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->create('a.png', 6 * 1024), 'uji', ['png']);

        $this->assertTrue($r['IsError'], 'Pesan bilang maks 5 MB, file 6 MB harus ditolak.');
    }

    public function test_svg_ditolak_di_modul_logo_client(): void
    {
        Storage::fake();
        $svg = UploadedFile::fake()->createWithContent('x.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');

        $this->actingAs($this->admin())->post('/patra/client-logo', ['name' => 'X', 'logo' => $svg]);

        $this->assertSame(0, \App\Models\ClientLogo::count());
    }

    public function test_informasi_menolak_file_php(): void
    {
        Storage::fake();
        $php = UploadedFile::fake()->createWithContent('shell.php', '<?php system($_GET[1]);');

        $this->actingAs($this->admin())->post('/patra/informasi', ['title' => 'Uji', 'file_path' => $php]);

        $this->assertSame(0, \App\Models\Informasi::count());
        $this->assertEmpty(Storage::allFiles());
    }

    public function test_pesan_error_internal_tidak_bocor_ke_pengguna(): void
    {
        $this->assertSame(
            \App\Helpers\ErrorHelper::GENERIC,
            \App\Helpers\ErrorHelper::userMessage(new \RuntimeException('SQLSTATE[23000]: tabel rahasia'))
        );
        $this->assertSame(
            'Data tidak ditemukan',
            \App\Helpers\ErrorHelper::userMessage(new \App\Exceptions\UserFacingException('Data tidak ditemukan'))
        );
    }
}
