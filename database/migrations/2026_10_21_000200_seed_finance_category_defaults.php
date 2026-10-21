<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $groups = [
            'masuk' => [
                'Pendapatan Jasa / Proyek' => ['Website', 'Aplikasi', 'Desain / Flyer', 'Joki Tugas', 'Lainnya'],
                'Pendapatan Lainnya' => [],
            ],
            'keluar' => [
                'Gaji & Bonus' => ['Gaji Developer', 'Gaji Broker / Sosmed', 'Bonus'],
                'Hosting & Domain' => [],
                'Operasional' => ['Biaya Admin Bank', 'ATK & Perlengkapan'],
                'Marketing & Iklan' => [],
                'Lainnya' => [],
            ],
        ];

        $urutanGroup = 0;
        foreach ($groups as $type => $categories) {
            foreach ($categories as $name => $children) {
                $urutanGroup++;

                $parentId = DB::table('finance_categories')->insertGetId([
                    'name' => $name,
                    'type' => $type,
                    'parent_id' => null,
                    'urutan' => $urutanGroup,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $urutanChild = 0;
                foreach ($children as $childName) {
                    $urutanChild++;
                    DB::table('finance_categories')->insert([
                        'name' => $childName,
                        'type' => $type,
                        'parent_id' => $parentId,
                        'urutan' => $urutanChild,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('finance_categories')->whereNotNull('parent_id')->delete();
        DB::table('finance_categories')->whereNull('parent_id')->delete();
    }
};
