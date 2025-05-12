<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'payment_status',
        'order_status',
        'date',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('default', 1)->first(); // single image relaton with order
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable'); // multiple image relaton with order
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'unit_price', 'total_price');  // to get additional pivot data
    }

    public function invoice()
    {
        return $this->hasOne(PaymentInvoice::class);
    }

    public function paymentInvoices()
    {
        return $this->belongsToMany(PaymentInvoice::class, 'order_payment_invoice');
    }


    public function invoices()
    {
        return $this->hasMany(PaymentInvoice::class, 'order_id');
    }

    public function isPaid()
    {
        return $this->paymentInvoices()->where('is_paid', true)->exists();
    }

    public function getPaymentInvoice()
    {
        $paymentInvoices = $this->paymentInvoices()->get();
    
        return !$paymentInvoices->isEmpty() ? $paymentInvoices->first() : null;
    }

    public function updateInvoiceTotalAmount()
    {
        $paymentInvoices = $this->paymentInvoices;
        foreach ($paymentInvoices as $invoice) {
            $totalAmount = $invoice->orders()->sum('total_amount');
            $invoice->update(['total_amount' => $totalAmount]);
        }
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productBalances() {
        return $this->hasMany(UserProductBalance::class);
    }

    
}