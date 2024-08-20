<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Package\app\Models\Package;

class PriceGreaterThanMinimum implements ValidationRule
{
    protected ?Package $package;

    protected string $date;

    public function __construct(Package $package, string $date)
    {
        $this->package = $package;
        $this->date = $date;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (! $this->package) {
            $fail('پکیج مورد نظر یافت نشد!');

            return;
        }

        $price = $this->package->getPriceForDate($this->date);

        if ($value < $price->getMinimumPrice()) {
            $fail('قیمت وارد شده باید حداقل برابر با قیمت تعیین شده برای تاریخ مشخص شده باشد.');
        }
    }

    public function message(): string
    {
        return 'قیمت وارد شده باید حداقل برابر با قیمت تعیین شده برای تاریخ مشخص شده باشد.';
    }
}
