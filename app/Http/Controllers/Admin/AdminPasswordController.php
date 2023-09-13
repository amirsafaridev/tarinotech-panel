<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPassword\UpdateRequest;
use App\Models\Admin;
use Exception;

class AdminPasswordController extends Controller
{
    public function index(Admin $admin)
    {
        $title = trans('panel.admin.edit_password');
        $routeUpdate = route('admin.admin.password.update', $admin->id);

        return view('admin.admin_password.edit', compact('title', 'routeUpdate', 'admin'));
    }

    public function update(UpdateRequest $request, Admin $admin)
    {
        try {
            $admin->update([
                'password' => bcrypt('password'),
            ]);

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.index'),
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {

            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }
}
