<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;




class Country implements ValidationRule
{
    //    /**
    //     * Run the validation rule.
    //     *
    //     * @param  \Closure(String): \Illuminate\Translation\PotentiallyTranslatedString  $fail
    //     */
    //
    //    {
    //        //
    //    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Define the allowed gender values
        //INDIA,USA,OTHERS
        $allowedCountry = ['I', 'U', 'O'];
        if (!in_array($value, $allowedCountry))
            $fail('The :attribute must be one of the following: male, female, other.');
    }
}
