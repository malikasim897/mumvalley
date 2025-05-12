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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('payment_invoices')->onDelete('cascade');
            $table->decimal('invoice_amount', 10, 2);
            $table->decimal('refund_amount', 10, 2);
            $table->boolean('is_refunded')->default(false);
            $table->text('refund_reason')->nullable();
            $table->date('refunded_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
