<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_invoice_id',
        'user_id',
        'total_invoice_amount',
        'paid_amount',
        'remaining_amount',
        'payment_method',
        'description',
    ];

    /**
     * The parent invoice this payment belongs to.
     */
    public function paymentInvoice()
    {
        return $this->belongsTo(PaymentInvoice::class);
    }

    /**
     * The user who made this payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
