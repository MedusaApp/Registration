<?php
namespace Personality\Registration;

use Illuminate\Support\Facades\Validator;

trait RegisterValidation
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:25'],
            'middle_name' => ['string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'address_1' => ['required', 'string', 'max:100'],
            'address_2' => ['string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'state_province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:25'],
            'country' => ['required', 'string', 'max:3'],
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => ['max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}