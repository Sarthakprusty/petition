<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;




class Orgtype implements ValidationRule
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
        //internal,ministry/departments,state
        $allowedOrgType = ['M', 'S', 'I'];
        if (!in_array($value, $allowedOrgType))
            $fail('The :attribute must be one of the following: internal,ministry/departments,state	');
    }
}
