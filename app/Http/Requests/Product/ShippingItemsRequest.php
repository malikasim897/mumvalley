<?php

namespace App\Http\Requests\Parcel;

use Illuminate\Foundation\Http\FormRequest;

class ShippingItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required',
            "tax_modality" => "required",
            'shippment_value' => 'required|numeric|gt:0',
            'shippingItems.*.sh_code' => 'required',
            'shippingItems.*.description' => 'required|string|max:200',
            'shippingItems.*.quantity' => 'required|numeric',
            'shippingItems.*.value' => 'required|numeric',
            'return_parcel' => 'required',
            // 'shippingItems.*.total' => 'numeric', 
            // 'shippingItems.*.contains_goods' => 'in:is_battery,is_perfume,is_flammable',
        ];
    }

    public function messages()
    {
        return [
            'service_id.required' => 'Please select the shipping service.',
            'shipment_value.required' => 'The shipment value freight field is required.',
            'shipment_value.numeric' => 'The shipment value freight must be a numeric value.',
            'shipment_value.gt' => 'The shipment value freight must be greater than 0.',
            'shippingItems.*.sh_code.required' => 'The Harmonized Code is required',
            'shippingItems.*.description.required' => 'The description field is required.',
            'shippingItems.*.quantity.required' => 'The Quantity is required',
            'shippingItems.*.quantity.numeric' => 'The Quantity must be a numeric value.',
            'shippingItems.*.value.required' => 'The Value is required',
            'shippingItems.*.value.numeric' => 'The Value must be a numeric value.',
            "shippingItems.*.description.max" => "The descrition must not be greater than 200 characters.",
            'return_parcel.required' => 'Please select return option',
        ];
    }
}
