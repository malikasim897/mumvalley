<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_inventory_id',
        'delivered_units',
        'returned_units',
        'remaining_customer_units',
        'remaining_stock_units',
        'pack_quantity',
        'unit_price',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productInventory()
    {
        return $this->belongsTo(ProductInventory::class);
    }

    public function productBalance() {
        return $this->hasOne(UserProductBalance::class);
    }

}
