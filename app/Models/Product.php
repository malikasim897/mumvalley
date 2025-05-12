<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'unique_id',
        'name',
        'type',
        'image',
        'returnable',
    ];

    /**
     * Get the user that owns the warehouse product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function charges()
    {
        return $this->hasMany(ProductCharge::class);
    }

    /**
     * Get the latest charge for the product based on creation timestamp.
     */
    public function latestCharge()
    {
        return $this->hasOne(ProductCharge::class)->latest();                                                                                                   
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'unit_price', 'total_price'); // Optional: include pivot data
    }

    public function inventories()
    {
        return $this->hasMany(ProductInventory::class, 'product_id');
    }

    public function lastInventory()
    {
        return $this->hasOne(ProductInventory::class, 'product_id')->oldestOfMany();
    }

    public function latestConfirmedInventory()
    {
        return $this->hasOne(ProductInventory::class, 'product_id')
            ->where('status', true)
            ->orderBy('updated_at', 'desc');
    }

    public function storageCharges()
    {
        return $this->hasMany(ProductStorageCharge::class);
    }

    public function latestStorageCharge()
    {
        return $this->hasOne(ProductStorageCharge::class)->latest();                                                                                                   
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getUserBalance($userId)
    {
        return $this->userBalances()
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->value('remaining_units');
    }

    public function userBalances()
    {
        return $this->hasMany(UserProductBalance::class);
    }

}
