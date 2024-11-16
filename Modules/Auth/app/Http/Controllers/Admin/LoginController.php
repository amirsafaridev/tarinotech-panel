<?php

namespace Modules\Auth\App\Http\Controllers\Admin;

use App\Domain\Jobs\OtpGenerateJob;
use App\Enums\Database\Admin\OtpSendWay;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Modules\Admin\app\Models\Admin;
use Modules\Auth\app\Http\Requests\Api\Auth\LoginRequest;
use Modules\Auth\app\Notifications\Admin\EmilOtpNotification;
use Modules\Auth\app\Notifications\Admin\SmsOtpNotification;

class LoginController extends Controller
{
    const INDEX_TITLE = 'تارینوتک - ورود';

    public function __construct()
    {
        $this->middleware('admin.guest:admin', ['except' => 'logout']);
        Session::forget(config('auth.otp_code_session_key'));
    }

    public function index()
    {
        $title = self::INDEX_TITLE;

        if (app()->isLocal()) {
            Auth::guard('admin')->loginUsingId(1);
        }

        return view('auth::admin.login', compact('title'));
    }

    public function login(LoginRequest $request, OtpGenerateJob $otpGenerateJob): RedirectResponse
    {
        try {
            $admin = $this->validateAdminCredentials($request->only('email', 'password'));

            if (! $admin) {
                return to_route('auth.admin.login')
                    ->with('error', __('auth.invalid_credentials'));
            }

            $this->generateAndSendOtp($otpGenerateJob, $admin);
            $this->storeOtpEmailInSession($admin);

            return to_route('auth.admin.verify')
                ->with('success', __('auth.otp_sent'));

        } catch (Exception $exception) {
            report($exception);

            return back()->with('error', __('auth.login_error'));
        }
    }

    private function validateAdminCredentials(array $credentials): ?Admin
    {
        $admin = Admin::query()
            ->where('email', $credentials['email'])
            ->where('is_block', false)
            ->first();

        return $admin && Hash::check($credentials['password'], $admin->password) ? $admin : null;
    }

    private function generateAndSendOtp(OtpGenerateJob $otpGenerateJob, Admin $admin): void
    {
        $otpCode = $otpGenerateJob->handle($admin, $admin->email);

        if (app()->isProduction()) {
            $this->sendOtpNotification($admin, $otpCode);
        }

    }

    private function sendOtpNotification(Admin $admin, string $otpCode): void
    {
        $notification = $admin->otp_send_way === OtpSendWay::EMAIL
            ? new EmilOtpNotification($otpCode)
            : new SmsOtpNotification($otpCode);

        $admin->notify($notification);
    }

    private function storeOtpEmailInSession(Admin $admin): void
    {
        Session::put(config('auth.otp_code_session_key'), $admin->email);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();

        return redirect()->route('auth.admin.login')
            ->with('success', __('auth.logged_out'));
    }
}
