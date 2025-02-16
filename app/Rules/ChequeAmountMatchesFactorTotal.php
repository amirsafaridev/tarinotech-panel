<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Factor\app\Models\Factor;

class ChequeAmountMatchesFactorTotal implements ValidationRule
{
    protected int $factorId;

    public function __construct($factorId)
    {
        $this->factorId = $factorId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $factor = Factor::query()
            ->where('id', $this->factorId)
            ->first();

        if (! $factor || $value != $factor->final_price) {
            $fail('مقدار :attribute باید برابر با مجموع آیتم فاکتور مشخص شده باشد.');
        }
    }
}
