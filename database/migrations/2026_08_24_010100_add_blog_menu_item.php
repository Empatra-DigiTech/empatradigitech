<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Menu::firstOrCreate(
            ['title' => 'Blog'],
            ['title' => 'Blog']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Menu::where('title', 'Blog')->delete();
    }
};
