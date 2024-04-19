<?php

namespace Modules\Factor\app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\Factor\app\Models\Factor;

class FactorController extends Controller
{
    const PAYMENT_TITLE = 'پرداخت فاکتور';

    public function index($identify)
    {
        $title = self::PAYMENT_TITLE;

        $factor = Factor::query()
            ->with(['items', 'project'])
            ->whereHas('items')
            ->where('identify', $identify)
            ->firstOrFail();

        return view('factor::web.factor', compact('title', 'factor'));

    }
}
