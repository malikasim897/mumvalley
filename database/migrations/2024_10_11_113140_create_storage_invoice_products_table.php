<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_invoice_products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('storage_invoice_id'); // Reference to storage invoice
            $table->unsignedBigInteger('product_id'); // Reference to product
            $table->decimal('percentage_limit', 5, 2); // Percentage limit for storage usage
            $table->decimal('shipped_percentage', 5, 2); // Shipped percentage of product
            $table->unsignedInteger('shipped_units'); // Number of units shipped
            $table->unsignedInteger('remaining_units'); // Remaining units in inventory
            $table->decimal('storage_charges', 10, 2); // Storage Charges for that months
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('storage_invoice_id')->references('id')->on('storage_invoices')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_invoice_products');
    }
}
