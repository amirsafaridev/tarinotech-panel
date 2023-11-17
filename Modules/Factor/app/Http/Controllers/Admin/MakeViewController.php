<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ajax\MakeInstallmentsRequest;
use App\Models\TransactionCategory;
use View;

class MakeViewController extends Controller
{
    const VIEW_ITEM_CREATED = 'ایتم فاکتور بارگزاری شد';

    public function getItem()
    {
        $categories = TransactionCategory::query()->get();
        $view = (string) View::make('factor::admin.item.item', compact('categories'));

        return response()->json([
            'html' => $view,
            'message' => self::VIEW_ITEM_CREATED,
        ]);
    }

    public function getInstallment(MakeInstallmentsRequest $request)
    {
        $installments = $this->calculatePercentages($request->input('price'));
        $categories = TransactionCategory::query()->get();
        $itemViews = '';
        $installmentTypes = [
            '70_percent' => 'بیعانه',
            '15_percent_1' => 'قسط دوم',
            '15_percent_2' => 'قسط سوم',
        ];

        foreach ($installmentTypes as $installmentKey => $installmentLabel) {
            $item = $this->getFactorItemMake($installments[$installmentKey], $installmentLabel);
            $index = array_search($installmentKey, array_keys($installmentTypes));
            $itemViews .= View::make('factor::admin.item.item', compact('categories', 'item', 'index'));
        }

        return response()->json([
            'html' => $itemViews,
            'message' => self::VIEW_ITEM_CREATED,
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
        $taxRate = config('factor.tax');

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
