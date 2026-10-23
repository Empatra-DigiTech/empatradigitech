<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pengunjung yang sama harus boleh mengirim pesan lebih dari sekali.
     * Sebelumnya kolom email UNIQUE sehingga pesan kedua selalu gagal.
     */
    public function up(): void
    {
        Schema::table('kontaks', function (Blueprint $table) {
            $table->dropUnique('kontaks_email_unique');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('kontaks', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->unique('email');
        });
    }
};
