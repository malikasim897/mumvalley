<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $method = request()->method();
        $orderId = $this->route('product');
        switch($method)
        {
            case 'POST':
            return [
                // 'user_id' => 'required',
                'product_name' => 'required',
                'purchased_units' =>'required',
                'unit_price' =>'required',
                'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'weight' => 'required|numeric|gt:0',
                // 'unit' => 'required|in:kg/cm,lbs/in',
                // 'length' => 'required|numeric|gt:0',
                // 'width' => 'required|numeric|gt:0',
                // 'height' => 'required|numeric|gt:0',
            ];
            break;
            case 'PUT':
            return [
                // 'user_id' => 'required',
                'product_name' => 'required',
                'purchased_units' =>'required',
                'unit_price' =>'required',
                'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'weight' => 'required|numeric|gt:0',
                // 'unit' => 'required|in:kg/cm,lbs/in',
                // 'length' => 'required|numeric|gt:0',
                // 'width' => 'required|numeric|gt:0',
                // 'height' => 'required|numeric|gt:0',
            ];
            break;
        }
    }

    /**
     * Get the error messages for the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'user_id.required' => 'The User Name is required.',
            'product_name.required' => 'The Product Name is required.',
            'purchased_units.required' => 'Purchased Units Quantity is required.',
            'unit_price.required' => 'Product Unit Price is required.',
            'gt' => 'The :attribute must be greater than 0.',
            'weight.required' => 'Weight is required.',
            'height.required' => 'Height is required.',
            'length.required' => 'Length is required.',
            'width.required' => 'Width is required.',
        ];
    }
}
