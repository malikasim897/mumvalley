<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TrackingRequest extends FormRequest
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
        switch($method)
        {
            case 'POST':
            return [
                'tracking_order' => 'required',
            ];
            break;
            case 'GET':
            return [
                'tracking_order' => 'required',
            ];
            break;
            case 'PUT':
            return [
                'tracking_order' => 'required',
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
           'tracking_order.required' => 'The Tracking Number field is required.',
        ];
    }
}
