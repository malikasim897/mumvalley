<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'transaction_id',
        'charge_month',
        'total_amount',
        'paid_amount',
        'paid_by',
        'is_paid',
        'payment_type',
        'payment_receipt',
        'payment_date',
        'cancelled',
    ];

    // Relationship to the user who created/owns the invoice
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Product via StorageInvoiceProduct
    public function products()
    {
        return $this->hasMany(StorageInvoiceProduct::class);
    }

    // Relationship to the user who made the payment, if applicable
    public function paidByUser()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // public function transaction()
    // {
    //     return $this->belongsTo(Transaction::class, 'transaction_id');
    // }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'invoice');
    }

    public static function generateUUID($prefix='SI-')
    {
        return uniqid($prefix);
    }

    public function isPaid()
    {
        return $this->is_paid;
    }

    public function latestTransaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
