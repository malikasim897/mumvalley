<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStorageCharge extends Model
{
    use HasFactory;

    // Table name (optional if you follow Laravel's naming convention)
    protected $table = 'product_storage_charges';

    // Fillable fields for mass assignment
    protected $fillable = [
        'product_id',
        'inventory_id',
        'storage_size',
        'ship_percentage',
        'storage_charge',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Define the relationship with the ProductInventory model
    public function inventory()
    {
        return $this->belongsTo(ProductInventory::class);
    }
}
