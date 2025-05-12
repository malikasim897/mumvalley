<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCharge extends Model
{
    use HasFactory;

    // Table name (if not following Laravel naming convention)
    protected $table = 'product_charges';

    // Fillable fields for mass assignment
    protected $fillable = [
        'product_id',
        'user_id',
        'unit_charge',
        'additional_unit_charge',
    ];

    // Define the relationship to the Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // You can also define a relationship to the User model if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
