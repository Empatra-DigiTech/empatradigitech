<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Real achievement figures shown in the "About Us" section.
     * Kept as free-text strings (e.g. "20+") so the admin can enter
     * exactly what is true — no hardcoded/fabricated numbers in the code.
     */
    public function up(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengaturans', 'stat_projects')) {
                $table->string('stat_projects')->nullable()->after('website_email');
            }
            if (!Schema::hasColumn('pengaturans', 'stat_clients')) {
                $table->string('stat_clients')->nullable()->after('stat_projects');
            }
            if (!Schema::hasColumn('pengaturans', 'stat_industries')) {
                $table->string('stat_industries')->nullable()->after('stat_clients');
            }
            if (!Schema::hasColumn('pengaturans', 'stat_years_experience')) {
                $table->string('stat_years_experience')->nullable()->after('stat_industries');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn(['stat_projects', 'stat_clients', 'stat_industries', 'stat_years_experience']);
        });
    }
};
