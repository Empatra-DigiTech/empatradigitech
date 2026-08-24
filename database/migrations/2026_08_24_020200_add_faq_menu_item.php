<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Menu::firstOrCreate(
            ['title' => 'FAQ'],
            ['title' => 'FAQ']
        );
    }

    public function down(): void
    {
        Menu::where('title', 'FAQ')->delete();
    }
};
