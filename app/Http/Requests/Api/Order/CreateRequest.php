<?php

namespace App\Http\Requests\Api\Order;

use App\Models\Order;
use App\Rules\NcmValidator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
class CreateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
                'parcel.merchant' => 'required',
                'parcel.carrier' => 'required',
                'parcel.tracking_id' => 'required|max:22|unique:orders,tracking_id',
                'parcel.additional_reference' => 'required|max:22|unique:orders,additional_reference',
                'parcel.date' => 'required',
                'parcel.image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'parcel.weight' => 'required|numeric|gt:0',
                'parcel.unit' => 'required|in:kg/cm,lbs/in',
                'parcel.length' => 'required|numeric|gt:0',
                'parcel.width' => 'required|numeric|gt:0',
                'parcel.height' => 'required|numeric|gt:0',
                "parcel.freight_rate" => "required|numeric|min:0.01",
                "sender.first_name"=>"required",
                "sender.last_name"=>"required",
                "sender.email"=>"required",
                "sender.phone"=>"required",
                // "sender.country_id"=>"required",
                // "sender.country_name"=>"required",
                // "sender.country_code"=>"required",
                // "sender.state_id"=>"required",
                // "sender.state_name"=>"required",
                // "sender.state_code"=>"required",
                // "sender.address"=>"required",
                // "sender.city"=>"required",
                // "sender.street_no"=>"required",
                // "sender.zipcode"=>"required",
                // "sender.tax_id"=>"required",

                "recipient.first_name"=>"required",
                "recipient.last_name"=>"required",
                "recipient.email"=>"required",
                "recipient.phone"=>"required",
                "recipient.country_id"=>"required",
                "recipient.country_name"=>"required",
                "recipient.country_code"=>"required",
                "recipient.state_id"=>"required",
                "recipient.state_name"=>"required",
                "recipient.state_code"=>"required",
                "recipient.address"=>"required",
                "recipient.city"=>"required",
                "recipient.street_no"=>"required",
                "recipient.zipcode"=>"required",
                "recipient.tax_id"=>"required",

                
                'items.*.sh_code' => 'required',
                'items.*.description' => 'required|string',
                'items.*.quantity' => 'required|numeric',
                'items.*.value' => 'required|numeric',
            ];
            if ($request->recipient['country_id'] == 'BR' || $request->recipient['country_id'] == 30) {
                $rules['recipient.phone'] = 'required|string|regex:/^\+55\d{8,12}$/';
            }
            return $rules;
    }

    public function messages()
    {
        return [
            'parcel.merchant.required' => 'The Sender Name is required.',
            'parcel.carrier.required' => 'Customer nick name is required.',
            'parcel.tracking_id.required' => 'Order number is required.',
            'parcel.gt' => 'The :attribute must be greater than 0.',
            'parcel.weight.required' => 'Weight is required.',
            'parcel.height.required' => 'Height is required.',
            'parcel.length.required' => 'Length is required.',
            'parcel.width.required' => 'Width is required.',
            'recipient.phone.regex' => 'Please enter a valid phone number in international format. Example: +551234567890',
            'items.*.sh_code.required' => 'The Harmonized Code is required',
            'items.*.description.required' => 'The description field is required.',
            'items.*.quantity.required' => 'The Quantity is required',
            'items.*.quantity.numeric' => 'The Quantity must be a numeric value.',
            'items.*.value.required' => 'The Value is required',
            'items.*.value.numeric' => 'The Value must be a numeric value.', 
             
        ];
    }
}
