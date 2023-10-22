<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use View;

class FactorController extends Controller
{
    public function getViewItem()
    {
        return View::make('admin.factor_item.row-item');
    }
}
