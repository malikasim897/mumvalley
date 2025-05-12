<?php

namespace App\Enums;

enum OrderStatusEnum:string {
    case product_pending = 'confirmation_pending';
    case product_approved = 'confirmed';
    case payment_pending = 'payment_pending';
    case payment_done = 'payment_done';
    case shipped = 'shipped';
    case in_process = 'in_process';
    case cancelled = 'cancelled';

    public function description():string
    {
        return match($this)
        {
            self::product_pending => 'Out of Stock',
            self::product_approved => 'In Stock',
            self::payment_pending => 'Payment Pending',
            self::payment_done => 'Payment Done',
            self::shipped => 'Shipped',
            self::in_process => 'In Process',
            self::cancelled => 'Cancelled',
        };
    }

    public function cssClass():string
    {
        return match($this)
        {
            self::product_pending => 'badge rounded-pill badge-light-warning',
            self::product_approved => 'badge rounded-pill badge-light-success',
            self::payment_pending => 'badge rounded-pill badge-light-warning',
            self::payment_done => 'badge rounded-pill badge-light-success',
            self::shipped => 'badge rounded-pill badge-light-primary',
            self::in_process => 'badge rounded-pill badge-light-dark',
            self::cancelled => 'badge rounded-pill badge-light-danger',
        };
    }

    public static function getDescription(string $value)
    {
        foreach (self::cases() as $item) {
            if($item->value == $value){
                return $item->description();
            }
        }
    }

    public static function getCssClass(string $value)
    {
        foreach (self::cases() as $item) {
            if($item->value == $value){
                return $item->cssClass();
            }
        }
    }


    public static function getAllWithDescription()
    {
        return array_map(fn(OrderStatusEnum $item) => [
            'name' => $item->value,
            'description'=> $item->description(),
        ], self::cases());
    }
}
