<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fnsku_price',
        'bubblewrap_price',
        'polybag_price',
        'small_box_price',
        'medium_box_price',
        'large_box_price',
        'additional_units_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
