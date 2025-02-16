<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ShabaNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // SHABA numbers should have 24 characters after 'IR' and must only contain numbers
        if (strlen($value) !== 26 || ! preg_match('/^IR[0-9]{24}$/', $value)) {
            $fail();
        }
    }

    public function message(): string
    {
        return 'شماره شبا :attribute معتبر نیست.';
    }
}
