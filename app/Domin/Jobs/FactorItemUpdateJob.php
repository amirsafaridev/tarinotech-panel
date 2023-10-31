<?php

namespace App\Domin\Jobs;

use App\Foundation\ValueObjects\Requests\FactorItemValues;
use App\Models\FactorItem;

class FactorItemUpdateJob
{
    public function handle(FactorItemValues $values, int $id)
    {
        $taxRate = 0.09;
        $price = $values->getPrice();
        $taxAmount = $price * $taxRate;
        $finalPrice = ($price + $taxAmount) - $values->getDiscount();

        FactorItem::query()
            ->where('id', $id)
            ->update([
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
