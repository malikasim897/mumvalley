<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'paid_by',
        'order_count',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'is_paid',
        'partial_paid',
        'cancelled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_payment_invoice');
    }

    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class,'invoice_id');
    // }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'invoice');
    }

    public static function generateUUID($prefix='PI-')
    {
        return uniqid($prefix);
    }

    public function isPaid()
    {
        return $this->is_paid;
    }

    public function getTotalAmountAttribute($value)                                             
    {
        return round($value,2);
    }

    public function differnceAmount()
    {
        if ($this->total_amount > $this->paid_amount) {
            return $this->total_amount - $this->paid_amount;
        }
        
        return null;
    }

    public function latestTransaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function invoicePayments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

}
