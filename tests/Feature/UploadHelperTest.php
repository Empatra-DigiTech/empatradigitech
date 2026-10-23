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

    // ---- Perbaikan Fase 2.2 (belum dikerjakan) ----

    public function test_daftar_ekstensi_kosong_tidak_boleh_menerima_apa_saja(): void
    {
        $this->pendingFix('Fase 2.2 (whitelist wajib)');

        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->create('shell.php', 1), 'uji', []);

        $this->assertTrue($r['IsError']);
    }

    public function test_isi_file_harus_cocok_dengan_ekstensi(): void
    {
        $this->pendingFix('Fase 2.2 (cek MIME sebenarnya)');

        Storage::fake();
        $palsu = UploadedFile::fake()->createWithContent('palsu.png', '<?php echo 1;');
        $r = UploadHelper::upload_file($palsu, 'uji', ['png']);

        $this->assertTrue($r['IsError']);
    }

    public function test_pesan_batas_ukuran_sesuai_batas_sebenarnya(): void
    {
        $this->pendingFix('Fase 2.2 (pesan 5 MB vs batas ~48 MB)');

        Storage::fake();
        $r = UploadHelper::upload_file(UploadedFile::fake()->create('a.png', 6 * 1024), 'uji', ['png']);

        $this->assertTrue($r['IsError'], 'Pesan bilang maks 5 MB, file 6 MB harus ditolak.');
    }
}
