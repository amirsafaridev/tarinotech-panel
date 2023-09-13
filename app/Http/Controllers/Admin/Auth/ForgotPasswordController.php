<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ForgetPasswordRequest;
use App\Models\Admin;
use App\Models\OtpCode;
use App\Notifications\Admin\Auth\OtpCodeEmail;
use App\Notifications\Admin\Auth\OtpCodeSms;
use Exception;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.guest:admin');
    }

    public function index()
    {
        $title = trans('panel.auth.forget.title');

        return view('admin.auth.passwords.forget', compact('title'));
    }

    public function sendOtpCode(ForgetPasswordRequest $request)
    {
        try {

            // Check User Exist
            $admin = Admin::query()
                ->where(function ($q) use ($request) {
                    $q->where('email', $request->input('identify'));
                    $q->orWhere('mobile', $request->input('mobile'));
                })
                ->where('has_access', true)
                ->first();

            if (! $admin) {
                return back()->with('warning', trans('panel.auth.forget.user_notfound'));
            }

            // Check Already Code Created
            $otp = OtpCode::query()
                ->whereDate('expired_at', '>', now())
                ->where('identify', $request->input('identify'))
                ->latest()
                ->first();

            if ($otp) {
                return back()->with('warning', trans('panel.auth.forget.otp.already_sent'));
            }

            // Create New Otp Code For Email or Mobile
            $code = Helper::randNumeric(4);
            $admin->otpCodes()->create([
                'identify' => $request->input('identify'),
                'expired_at' => now()->addMinutes(config('auth.otp_expire_in_minute')),
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'code' => $code,
            ]);

            // Send Notification To User
            if (filter_var($request->input('identify'), FILTER_VALIDATE_EMAIL)) {
                // Notification Email
                $admin->notify(new OtpCodeEmail($code));
            } else {
                // Notification Mobile
                $admin->notify(new OtpCodeSms($code));
            }

            return to_route('admin.password.reset')->with('success', trans('panel.auth.forget.otp.sent'));

        } catch (Exception $exception) {
            report($exception);

            return back()->with('error', trans('panel.auth.forget.error'));
        }
    }
}
