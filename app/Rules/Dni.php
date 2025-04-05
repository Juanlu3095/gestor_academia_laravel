<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Dni implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nifRegEx = '/^[0-9]{8}[A-Z]$/i';
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";

        if (!preg_match($nifRegEx, $value) || ($letras[(substr($value, 0, 8) % 23)] != strtoupper($value[8]))) {
            $fail('El DNI no es válido');
        }
        
    }
}
