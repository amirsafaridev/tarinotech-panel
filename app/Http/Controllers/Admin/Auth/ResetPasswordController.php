<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ResetPasswordRequest;
use App\Models\OtpCode;
use Auth;
use DB;
use Exception;
use Modules\Admin\app\Models\Admin;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.guest:admin');
    }

    public function index()
    {
        $title = trans('panel.auth.reset.title');

        return view('admin.auth.passwords.reset', compact('title'));
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
            $otp = OtpCode::query()
                ->has('user')
                ->with('user')
                ->where('code', $request->input('code'))
                ->where('expired_at', '>', now())
                ->first();

            if (! $otp) {
                return to_route('admin.password.reset')
                    ->with('error', trans('panel.auth.reset.otp.invalidate'));
            }

            DB::beginTransaction();

            /**
             * Update Admin Password Scenario
             *
             * @var Admin $admin
             */
            $admin = $otp->user;
            $this->adminPasswordUpdate($admin, $request->input('password'));

            DB::commit();

            Auth::guard('admin')->loginUsingId($otp->user_id);

            return redirect()->to(route('admin.dashboard'));
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return to_route('admin.password.reset')
                ->with('error', trans('panel.auth.reset.error'));
        }
    }

    private function adminPasswordUpdate(Admin $admin, string $password)
    {
        $admin->update([
            'password' => bcrypt($password),
        ]);

        $admin->otpCodes()->delete();
    }
}
