<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['website', 'app'])->comment('Tipe paket: website atau aplikasi');
            $table->string('nama_paket');
            $table->string('tagline')->nullable();
            $table->decimal('harga', 15, 2);
            $table->string('periode')->default('Per Project')->comment('Contoh: Per Project, Per Tahun, dll');
            $table->json('fitur')->nullable()->comment('Array fitur dalam format JSON');
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0)->comment('Untuk pengurutan tampilan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
