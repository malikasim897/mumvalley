<?php

namespace App\Models;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Invoice;
use App\Models\Deposit;
use App\Models\Balance;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [ 
        'po_box_number',
        'name',
        'email',
        'phone',
        'status',
        'password',
        'api_token',
        'api_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('default', 1);
    }



    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }
    
    public function invoices()
    {
        return $this->hasMany(PaymentInvoice::class);
    }
    
    public function warehouseProducts()
    {
        return $this->hasMany(Product::class);
    }

    public function getUserName()
    {
        if ($this->setting !== null) {
            // return $this->setting->first_name . ' ' . $this->setting->last_name;
            return $this->setting->last_name;
        }

        return $this->name;
    }

    public function getPoBoxUserName()
    {
        if ($this->setting !== null) {
            // return $this->setting->first_name . ' ' . $this->setting->last_name . ' | ' . $this->po_box_number;
            return $this->setting->last_name . ' | ' . $this->po_box_number;
        }

        return $this->name . ' | ' . $this->po_box_number;
    }

    public function userRates()
    {
        return $this->hasMany(UserRate::class, 'user_id');
    }

    public function latestUserRate()
    {
        return $this->hasOne(UserRate::class, 'user_id')->latestOfMany();
    }

    public function isActive()
    {
        return $this->status;
    }

    public function userSetting()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function getCountryAttribute()
    {
        return $this->userSetting ? $this->userSetting->country : null;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getProductBalance($productId)
    {
        return $this->productBalances()
            ->where('product_id', $productId)
            ->orderByDesc('id')
            ->value('remaining_units');
    }

    public function productBalances() {
        return $this->hasMany(UserProductBalance::class);
    }

}