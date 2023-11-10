<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidNumbers implements Rule
{

    private $invalidNumbers;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->invalidNumbers = [];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $phoneNumberArray)
    {
        // loop over the array
        foreach($phoneNumberArray as $phoneNumber)
        {
            if (!isDigicelNumber($phoneNumber) && !isTelesurNumber($phoneNumber)) {
                array_push($this->invalidNumbers, $phoneNumber);
            }
        }

        return count($this->invalidNumbers) == 0;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = "The following numbers are invalid Surinamese numbers: ";
        for ($i=0; $i < count($this->invalidNumbers); $i++) {
            $message .= $this->invalidNumbers[$i];
            if ($i == (count($this->invalidNumbers) - 1)) { // is last
                $message .= ".";
            } else {
                $message .= ", ";
            }
        }
        $message .= ' Please submit the number in the following format: "597XXXXXXX".';
        return $message;
    }
}
