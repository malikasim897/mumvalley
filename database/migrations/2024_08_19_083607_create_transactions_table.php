<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Polymorphic relationship for invoices
            $table->unsignedBigInteger('invoice_id');
            $table->string('invoice_type'); // This will store either 'PaymentInvoice' or 'StorageInvoice'
            
            $table->string('payment_intent_id')->nullable();
            $table->string('latest_charge_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->nullable();
            $table->string('created')->nullable();
            $table->date('transaction_date')->nullable();
            $table->enum('payment_type', ['direct_transfer', 'stripe'])->nullable();
            $table->enum('payment_for', ['order_invoice', 'storage_invoice'])->nullable();
            $table->string('payment_receipt')->nullable();
            $table->enum('status', ['succeeded', 'pending', 'declined'])->nullable();
            $table->enum('invoice_status', ['cancelled', 'shipped'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
