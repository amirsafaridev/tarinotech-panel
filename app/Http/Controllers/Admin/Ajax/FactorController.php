<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
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
}
