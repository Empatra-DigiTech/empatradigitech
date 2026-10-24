<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\PengaturanSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Basis semua test.
 *
 * - DB selalu SQLite in-memory (lihat phpunit.xml), jadi database asli/produksi
 *   TIDAK PERNAH tersentuh. Jalankan: `php artisan test`.
 * - Role Spatie (SuperAdmin/Admin/Moderator) di-seed otomatis sebelum tiap test.
 * - Test untuk perilaku yang BELUM diperbaiki (Fase 2-4) memakai pending();
 *   aktifkan dengan: RUN_PENDING=1 php artisan test
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Test tidak boleh bergantung pada hasil `npm run build` (public/build tidak ada di git/CI).
        $this->withoutVite();

        // Jaga-jaga: tolak menjalankan test ke selain SQLite in-memory.
        if (config('database.default') !== 'sqlite') {
            $this->fail('Test harus memakai SQLite. Periksa phpunit.xml.');
        }

        // Pengaturan & Menu wajib ada: layout publik/admin langsung error 500 bila kosong.
        $this->seed([RoleSeeder::class, PengaturanSeeder::class, MenuSeeder::class]);
    }

    protected function userWithRole(string $role): User
    {
        $user = User::factory()->create([
            'email' => strtolower($role) . '@test.local',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole($role);

        return $user;
    }

    protected function superAdmin(): User { return $this->userWithRole('SuperAdmin'); }
    protected function admin(): User { return $this->userWithRole('Admin'); }
    protected function moderator(): User { return $this->userWithRole('Moderator'); }

    /** Lewati test untuk perbaikan yang belum dikerjakan, kecuali RUN_PENDING=1. */
    protected function pendingFix(string $phase): void
    {
        if (! env('RUN_PENDING')) {
            $this->markTestSkipped("Menunggu perbaikan {$phase} (jalankan dengan RUN_PENDING=1).");
        }
    }
}
