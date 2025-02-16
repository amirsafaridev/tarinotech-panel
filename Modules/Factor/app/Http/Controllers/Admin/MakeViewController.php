<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Factor\app\Models\TransactionCategory;
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
}
