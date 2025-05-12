<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'invoice_id',
        'invoice_amount',
        'refund_amount',
        'is_refunded',
        'refund_reason',
        'refunded_date',
    ];

    /**
     * Relationship with Transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relationship with PaymentInvoice
     */
    public function invoice()
    {
        return $this->belongsTo(PaymentInvoice::class, 'invoice_id');
    }
}

