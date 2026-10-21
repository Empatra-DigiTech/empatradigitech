<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->date('date');
            $table->string('description');
            $table->foreignId('finance_category_id')->nullable()->constrained('finance_categories')->nullOnDelete();
            $table->enum('type', ['masuk', 'keluar']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method')->default('Transfer');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['date', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
