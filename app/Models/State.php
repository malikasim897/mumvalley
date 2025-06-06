<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_code',
        'fips_code',
        'iso2',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
