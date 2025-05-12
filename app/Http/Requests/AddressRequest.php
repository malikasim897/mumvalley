<?php

namespace App\Http\Requests;

use App\Rules\CountryPhoneNumber;
use App\Rules\PhoneNumberForCountry;
use App\Repositories\CountryRepository;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    protected $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }
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
        $country = ['code' => '', 'name' => ''];
        if(request()->country_id)
        {
            $country = $this->countryRepository->getCountry(request()->country_id);
        }
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => [
                'required',
                new PhoneNumberForCountry($country),
                'regex:/^\+(?:\d\s?){6,14}\d$/',
            ],
            'country_id' => 'required',
            'state_id' => 'required',
            'address' => 'required',
            'address_2' => 'required|max:50',
            'account_type' => 'required',
            'city' => 'required',
            'street_no' => 'required',
            'zipcode' => 'required',
            'tax_id'=> 'required',
        ];
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
            'last_name.required' => 'The last name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'country_id.required' => 'Please Select the country.',
            'phone.required' => 'The phone number field is required.',
            'phone.regex' => 'Phone number format (e.g., +1234567890).',
            'state_id.required' => 'Please Select the country State.',
            'address.required' => 'The Address field is required.',
            'address_2.required' => 'The Address 2 field is required.',
            'account_type.required' => 'The Account Type field is required.',
            'city.required' => 'The City field is required.',
            'street_no.required' => 'The street no field is required.',
            'zipcode.required' => 'The ZipCode field is required.',
            'tax_id.required' => 'The Tax id field is required.',
        ];
    }
}
