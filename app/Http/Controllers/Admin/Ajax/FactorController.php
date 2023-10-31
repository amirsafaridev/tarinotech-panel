<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ajax\MakeInstallmentsRequest;
use App\Models\TransactionCategory;
use View;

class FactorController extends Controller
{
    public function getViewItem()
    {

        $transactionCategories = TransactionCategory::query()->get();
        $factorItemView = (string) View::make('admin.factor_item.row-item', compact('transactionCategories'));

        return response()->json([
            'html' => $factorItemView,
            'message' => 'ایتم فاکتور بارگزاری شد',
        ]);
    }

    public function makeInstallments(MakeInstallmentsRequest $request)
    {
        $installments = $this->calculatePercentages($request->input('price'));
        $transactionCategories = TransactionCategory::query()->get();
        $factorItemsView = '';

        $installmentTypes = [
            '70_percent' => 'بیعانه',
            '15_percent_1' => 'قسط دوم',
            '15_percent_2' => 'قسط سوم',
        ];

        foreach ($installmentTypes as $installmentKey => $installmentLabel) {
            $item = $this->getFactorItemMake($installments[$installmentKey], $installmentLabel);
            $index = array_search($installmentKey, array_keys($installmentTypes));
            $factorItemsView .= View::make('admin.factor_item.row-item', compact('transactionCategories', 'item', 'index'));
        }

        return response()->json([
            'html' => $factorItemsView,
            'message' => 'ایتم فاکتور بارگزاری شد',
        ]);
    }

    private function calculatePercentages($price)
    {
        return [
            '70_percent' => round($price * 0.70),
            '15_percent_1' => round($price * 0.15),
            '15_percent_2' => round($price * 0.15),
        ];
    }

    private function getFactorItemMake(int $price, string $title): object
    {
        $taxRate = 0.09;

        return (object) [
            'id' => null,
            'title' => $title,
            'price' => $price,
            'tax_amount' => round($price * $taxRate),
            'discount' => 0,
            'final_price' => $price + round($price * $taxRate),
            'transaction_category_id' => 1,
        ];
    }
}
