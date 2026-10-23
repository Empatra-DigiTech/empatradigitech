<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Pagar regresi otorisasi: setiap route `patra.*` harus punya aturan role yang sama
 * dengan tests/fixtures/route_roles.json. Kalau sengaja mengubah hak akses,
 * perbarui fixture itu di commit yang sama agar perubahan terlihat di review.
 */
class RouteAuthorizationSnapshotTest extends TestCase
{
    private function current(): array
    {
        $map = [];
        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if (! $name || ! str_starts_with($name, 'patra.')) {
                continue;
            }
            $roles = array_values(array_filter(
                $route->gatherMiddleware(),
                fn ($m) => is_string($m) && str_starts_with($m, 'role:')
            ));
            $map[$name] = $roles[0] ?? null;
        }
        ksort($map);

        return $map;
    }

    public function test_hak_akses_semua_route_patra_sesuai_snapshot(): void
    {
        $expected = json_decode(file_get_contents(__DIR__ . '/../fixtures/route_roles.json'), true);

        $this->assertSame($expected, $this->current());
    }

    public function test_hanya_dashboard_yang_terbuka_untuk_semua_role(): void
    {
        $ungated = array_keys(array_filter($this->current(), fn ($r) => $r === null));

        $this->assertSame(['patra.patra.index'], $ungated);
    }
}
