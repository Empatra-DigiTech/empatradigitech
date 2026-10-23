<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Penutupan periode Buku Kas per bulan (format YYYY-MM).
     * Satu baris per bulan; riwayat tutup/buka dicatat juga di tabel log (Loggable).
     */
    public function up(): void
    {
        Schema::create('finance_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7)->unique();
            $table->boolean('is_closed')->default(true);
            $table->timestamp('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamp('reopened_at')->nullable();
            $table->unsignedBigInteger('reopened_by')->nullable();
            $table->string('reopen_reason', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_periods');
    }
};
