<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $orderId = $this->route('user');
        switch($method)
        {
            case 'PATCH':
            return [
            "po_box_number"  => 'required|unique:users,po_box_number,'.$orderId,
                'status'     => 'required',
                'role'       => 'required',
                'first_name' => 'nullable|string|max:255',
                'last_name'  => 'nullable|string|max:255',
                'email'      => 'required|email',
                'phone'      => [
                    'required',
                    'regex:/^\+(?:\d\s?){6,14}\d$/',
                ],
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
            'first_name.required' => 'The first name field is required.',
            'last_name.required'  => 'The last name field is required.',
            'email.required'      => 'The email field is required.',
            'phone.required'      => 'The phone number field is required.',
            'phone.regex'         => 'Please enter a valid international phone number format (e.g., +1234567890).',
        ];
    }
}
