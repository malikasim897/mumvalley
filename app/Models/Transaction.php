<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's pluralization convention
    // protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'invoice_id',
        'invoice_type',
        'payment_intent_id',
        'latest_charge_id',
        'payment_method_id',
        'amount',
        'currency',
        'created',
        'transaction_date',
        'payment_type',
        'payment_for',
        'payment_receipt',
        'status',
    ];

    /**
     * Define relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define relationship with the PaymentInvoice model.
     */

    // public function invoice()
    // {
    //     return $this->hasOne(PaymentInvoice::class);
    // }

    public function invoice()
    {
        return $this->morphTo();
    }
    
    public function paymentInvoices()
    {
        return $this->belongsTo(PaymentInvoice::class, 'invoice_id');
    }

    public function storageInvoices()
    {
        return $this->belongsTo(StorageInvoice::class, 'invoice_id');
    }
}
