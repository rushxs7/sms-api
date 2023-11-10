<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $phoneNumber)
    {
        return (isDigicelNumber($phoneNumber) || isTelesurNumber($phoneNumber));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The given number is not a valid surinamese number. Please submit the number in the following format: "597XXXXXXX".';
    }
}
