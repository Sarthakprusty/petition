<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;




class ActionOrg implements ValidationRule
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
        //N=No Action
        //F=Forward to Central Govt. Ministry/Department
        //M=Miscellaneous

        $allowedAcknowledgement = ['N', 'F','M'];
        if (!in_array($value, $allowedAcknowledgement))
            $fail('The :attribute must be one of the following: male, female, other.');
    }
}
