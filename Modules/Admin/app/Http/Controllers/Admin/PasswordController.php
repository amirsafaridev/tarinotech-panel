<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Modules\Admin\app\Http\Requests\Admin\UpdatePasswordRequest;
use Modules\Admin\app\Models\Admin;

class PasswordController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پرسنل - ویرایش گذرواژه';

    public function index(Admin $admin)
    {
        $title = self::INDEX_TITLE;

        return view('admin::admin.password.edit', compact('title', 'admin'));
    }

    public function update(UpdatePasswordRequest $request, Admin $admin)
    {
        try {
            $admin->update([
                'password' => bcrypt($request->input('password')),
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
