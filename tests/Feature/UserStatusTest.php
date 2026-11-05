<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

/** M1: status user (Aktif/Tidak Aktif) harus benar-benar tersimpan dan dipakai. */
class UserStatusTest extends TestCase
{
    private function payload(array $override = []): array
    {
        return array_merge([
            'name' => 'Pengguna Baru',
            'email' => 'baru@test.local',
            'roles' => 'Moderator',
            'status' => 0,
            'password' => 'RahasiaBaru123',
            'password_confirmation' => 'RahasiaBaru123',
        ], $override);
    }

    public function test_store_menyimpan_status_nonaktif(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('patra.users.store'), $this->payload(['status' => 0]))
            ->assertRedirect(route('patra.users.index'));

        $this->assertSame(0, (int) User::where('email', 'baru@test.local')->value('status'));
    }

    public function test_store_menyimpan_status_aktif(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('patra.users.store'), $this->payload(['status' => 1]));

        $this->assertSame(1, (int) User::where('email', 'baru@test.local')->value('status'));
    }

    public function test_status_di_luar_pilihan_ditolak(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('patra.users.store'), $this->payload(['status' => 7]));

        $this->assertDatabaseMissing('users', ['email' => 'baru@test.local']);
    }

    public function test_update_mengubah_status(): void
    {
        $target = $this->moderator();

        $this->actingAs($this->superAdmin())
            ->put(route('patra.users.update', $target->id), [
                'name' => $target->name,
                'email' => $target->email,
                'roles' => 'Moderator',
                'status' => 0,
            ])
            ->assertRedirect(route('patra.users.index'));

        $this->assertSame(0, (int) $target->fresh()->status);
    }

    public function test_super_admin_tidak_bisa_menonaktifkan_dirinya_sendiri(): void
    {
        $me = $this->superAdmin();

        $this->actingAs($me)
            ->put(route('patra.users.update', $me->id), [
                'name' => $me->name,
                'email' => $me->email,
                'roles' => 'SuperAdmin',
                'status' => 0,
            ]);

        $this->assertSame(1, (int) $me->fresh()->status);
    }

    public function test_user_nonaktif_tidak_bisa_di_impersonate(): void
    {
        $target = $this->moderator();
        $target->update(['status' => 0]);

        $this->actingAs($this->superAdmin())
            ->get(route('patra.users.impersonate', $target->id))
            ->assertRedirect(route('patra.users.index'));

        $this->assertFalse(app('impersonate')->isImpersonating());
    }
}
