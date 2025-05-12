<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_invoices', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->uuid('uuid')->unique(); // Unique identifier for the invoice
            $table->unsignedBigInteger('user_id'); // Reference to user (creator or owner)
            $table->string('transaction_id')->nullable(); // Transaction ID (nullable if not available yet)
            $table->string('charge_month'); // Month for which the charge is applied (e.g., '2024-10')
            $table->decimal('total_amount', 10, 2); // Total invoice amount
            $table->decimal('paid_amount', 10, 2)->nullable(); // Paid amount (nullable if not paid yet)
            $table->unsignedBigInteger('paid_by')->nullable(); // User ID of the person who made the payment
            $table->boolean('is_paid')->default(false); // Whether the invoice is paid
            $table->enum('payment_type', ['direct_transfer', 'stripe'])->nullable(); // Payment method
            $table->string('payment_receipt')->nullable(); // Receipt for payment (nullable)
            $table->date('payment_date')->nullable(); // Payment date (nullable if unpaid)
            $table->boolean('cancelled')->default(false); // Whether the invoice has been cancelled
            $table->softDeletes(); // Soft delete for invoice
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paid_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_invoices');
    }
}
