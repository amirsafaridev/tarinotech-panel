<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\PermissionService;

class PermissionController extends Controller
{
    public function sync()
    {
        resolve(PermissionService::class)->sync();

        return back()->with('success', trans('panel.permission.sync-success'));
    }
}
