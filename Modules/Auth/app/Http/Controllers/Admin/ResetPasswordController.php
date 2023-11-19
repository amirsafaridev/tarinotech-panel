<?php

namespace Modules\Auth\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Exception;
use Modules\Admin\app\Models\Admin;
use Modules\Auth\app\Http\Requests\Admin\ResetPasswordRequest;
use Modules\Auth\app\Models\OtpCode;

use function bcrypt;
use function now;
use function redirect;
use function report;
use function route;
use function to_route;
use function trans;
use function view;

class ResetPasswordController extends Controller
{
    const INDEX_TITLE = 'پرتال تارینوتک - بازنشانی گذرواژه';

    public function __construct()
    {
        $this->middleware('admin.guest:admin');
    }

    public function index()
    {
        $title = self::INDEX_TITLE;

        return view('auth::admin.passwords.reset', compact('title'));
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
