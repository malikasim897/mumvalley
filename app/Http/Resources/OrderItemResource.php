<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "sh_code" => $this->sh_code,
            "description" => $this->description,
            "quantity" => $this->quantity,
            "value" => $this->value,
            "is_battery" => $this->contains_battery,
            "is_perfume" => $this->contains_perfume,
        ];
    }
}
