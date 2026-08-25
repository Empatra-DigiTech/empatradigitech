<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Menu::firstOrCreate(
            ['title' => 'Kalkulator'],
            ['title' => 'Kalkulator']
        );
    }

    public function down(): void
    {
        Menu::where('title', 'Kalkulator')->delete();
    }
};
