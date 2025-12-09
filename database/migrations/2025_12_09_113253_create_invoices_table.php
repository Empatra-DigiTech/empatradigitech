<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('from_name')->nullable()->change();
            $table->text('from_address')->nullable()->change();
            $table->string('bill_to_name')->nullable();
            $table->string('bill_to_address')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->string('ship_to_address')->nullable();
            $table->date('invoice_date');
            $table->string('payment_terms')->nullable();
            $table->date('due_date')->nullable();
            $table->string('po_number')->nullable();
            $table->json('items')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('theme', 50)->default('classic');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
