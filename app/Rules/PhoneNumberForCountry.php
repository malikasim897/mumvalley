<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumberForCountry implements Rule
{
    protected $country;

    public function __construct($country)
    {
        $this->country = $country;
    }

    public function passes($attribute, $value)
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        // $this->country = 'BR';
        try {
            // Remove spaces and other non-numeric characters from the input value
            $cleanedValue = preg_replace('/[^0-9]/', '', $value);

            // Prepend the plus sign (+) to indicate an international number
            $cleanedValue = '+' . $cleanedValue;

            $parsedPhoneNumber = $phoneNumberUtil->parse($cleanedValue, 'BR');

            // Check if the number is valid for the selected region
            return $phoneNumberUtil->isValidNumberForRegion($parsedPhoneNumber, 'BR');
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    public function message()
    {
        return 'Please enter a selected country phone number without space in international format for '.$this->country['name'].'.';
    }
}
