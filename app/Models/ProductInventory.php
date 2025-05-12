<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_image',
        'purchased_units',
        'total_units',
        'remaining_units',
        'unit_price',
        'total_price',
        'date',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function storageCharges()
    {
        return $this->hasMany(ProductStorageCharge::class);
    }

}
