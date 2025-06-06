<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "service_id" => $this->shipping_service_id,
            "merchant" => $this->merchant,
            "carrier" => $this->carrier,
            "tracking_id" => $this->tracking_id,
            "tracking_code" => $this->corrios_tracking_code,
            "customer_reference" => $this->customer_reference,
            "measurement_unit" => $this->measurement_unit,
            "weight" => $this->weight,
            "Volumetric_weight" => $this->getVolumnWeight($this->length, $this->width, $this->height, $this->isWeightInKg($this->measurement_unit)),
            "length" => $this->length,
            "width" => $this->width,
            "height" => $this->height,
            "order_date" => $this->order_date,
            "sender" => [
                "sender_first_name" => $this->sender_first_name,
                "sender_last_name" => $this->sender_last_name,
                "sender_email" => $this->sender_email,
                "sender_taxId" => $this->sender_taxId,
            ],
            "created_at" => $this->created_at,
            "warehouse_number" => $this->warehouse_number,
            "order_value" => $this->order_value,
            "shipping_service_name" => $this->shipping_service_name,
            "shipping_value" => $this->shipping_value,
            "prohibited_goods" => $this->dangrous_goods,
            "total" => $this->total,
            "discount" => $this->discount,
            "gross_total" => $this->gross_total,
            "recipient" => new OrderRecipientResource($this->recipient),
            "products" => OrderItemResource::collection($this->products),
        ];
    }

    public function isWeightInKg($measurement_unit)
    {
        return $measurement_unit == 'kg/cm' ? 'cm' : 'in';
    }
    public function getVolumnWeight($length, $width, $height, $unit)
    {
        $divisor = $unit == 'in' ? 166 : 6000;
        return round(($length * $width * $height) / $divisor, 2);
    }
}
