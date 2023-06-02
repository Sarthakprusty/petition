<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;




class Language implements ValidationRule
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
        //HINDI,ENGLISH,OTHERS
        $allowedLanguage = ['H', 'E', 'O'];
        if (!in_array($value, $allowedLanguage))
            $fail('The :attribute must be one of the following: male, female, other.');
    }
}
