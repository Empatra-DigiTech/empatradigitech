<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculator_features', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fitur');
            $table->decimal('harga_tambahan', 15, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculator_features');
    }
};
