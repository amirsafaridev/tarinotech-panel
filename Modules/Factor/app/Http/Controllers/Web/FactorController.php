<?php

namespace Modules\Factor\app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Factor\app\Models\Factor;

class FactorController extends Controller
{
    const PAYMENT_TITLE = 'پرداخت فاکتور';

    const FACTOR_NOT_FOUND = 'فاکتور مورد نظر یافت نشد!';

    public function index($identify)
    {
        $title = self::PAYMENT_TITLE;

        $factor = Factor::query()
            ->with(['items', 'project'])
            ->whereHas('items')
            ->where('identify', $identify)
            ->first();

        if (! $factor) {
            return view('factor::web.factor')
                ->with([
                    'message' => self::FACTOR_NOT_FOUND,
                    'warning' => true,
                ]);
        }

        return view('factor::web.factor', compact('title', 'factor'));

    }
}
