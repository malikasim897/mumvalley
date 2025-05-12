<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageInvoiceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'storage_invoice_id', 'product_id', 'percentage_limit', 
        'shipped_percentage', 'shipped_units', 'remaining_units', 'storage_charges',
    ];

    // Relationship with StorageInvoice
    public function storageInvoice()
    {
        return $this->belongsTo(StorageInvoice::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
