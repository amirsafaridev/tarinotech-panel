<?php

namespace App\Domain\Jobs;

use App\Foundation\ValueObjects\Requests\FactorItemValues;
use Modules\Factor\app\Models\FactorItem;

class FactorItemCreateJob
{
    public function handle(FactorItemValues $values): FactorItem
    {
        $taxRate = config('factor.tax');
        $price = $values->getPrice();
        $taxAmount = $price * $taxRate;
        $finalPrice = ($price + $taxAmount) - $values->getDiscount();

        return FactorItem::query()->create([
            'factor_id' => $values->getFactorId(),
            'title' => $values->getTitle(),
            'transaction_category_id' => $values->getTransactionCategoryId(),
            'discount' => $values->getDiscount(),
            'price' => $price,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'final_price' => $finalPrice,
        ]);
    }
}
