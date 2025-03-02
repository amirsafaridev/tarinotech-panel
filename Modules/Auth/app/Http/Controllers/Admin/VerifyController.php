<?php

namespace Modules\Auth\App\Http\Controllers\Admin;

use App\Enums\Database\Admin\OtpSendWay;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Modules\Admin\app\Models\Admin;
use Modules\Auth\App\Http\Requests\Admin\Auth\VerifyRequest;
use PragmaRX\Google2FA\Google2FA;

class VerifyController extends Controller
{
    private const PAGE_TITLE = 'تارینوتک - کد تایید';

    public function __construct()
    {
        $this->middleware('admin.guest:admin', ['except' => 'logout']);
    }

    public function index()
    {
        $title = self::PAGE_TITLE;

        return view('auth::admin.verify', compact('title'));
    }

    public function verify(VerifyRequest $request): RedirectResponse
    {
        try {

            $admin = $this->getAdminFromSession();

            if (! $admin) {
                return $this->redirectToLoginWithError();
            }

            if ($admin->otp_send_way == OtpSendWay::GOOGLE_AUTH) {
                return $this->verifyGoogleAuthenticatorCode($admin, $request->input('code'));
            } else {
                return $this->processOtpVerification($admin, $request->input('code'));
            }

        } catch (Exception $e) {
            report($e);

            return $this->handleUnexpectedError();
        }
    }

    private function verifyGoogleAuthenticatorCode(Admin $admin, string $inputCode): RedirectResponse
    {
        try {
            $google2FA = new Google2FA();
            $isValidCode = $google2FA->verifyKey($admin->google2fa_secret, $inputCode);

            if ($isValidCode) {
                return $this->loginAdmin($admin);
            }

            return $this->redirectBackWithError();
        } catch (Exception $e) {
            report($e);

            return back()->withErrors([
                'message' => __('auth.unexpected_error'),
            ]);
        }
    }

    private function getAdminFromSession(): ?Admin
    {
        return Admin::query()
            ->where('email', session(config('auth.otp_code_session_key')))
            ->where('is_block', false)
            ->first();
    }

    private function redirectToLoginWithError(): RedirectResponse
    {
        return redirect()->route('auth.admin.login')->withErrors([
            'message' => Lang::get('auth.invalid_admin'),
        ]);
    }

    private function processOtpVerification(Admin $admin, string $inputOtp): RedirectResponse
    {
        $storedOtp = $this->retrieveOtpCodeForAdmin($admin, $inputOtp);

        if ($storedOtp && $storedOtp === $inputOtp) {
            return $this->loginAdmin($admin);
        }

        return $this->redirectBackWithError();
    }

    private function retrieveOtpCodeForAdmin(Admin $admin, string $otpCode): ?string
    {
        if (app()->isLocal()) {
            return config('auth.development_otp');
        }

        return $admin->otpCodes()
            ->where('code', $otpCode)
            ->where('expired_at', '>', now())
            ->latest()
            ->first()?->code;
    }

    private function loginAdmin(Admin $admin): RedirectResponse
    {
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard.index');
    }

    private function redirectBackWithError(): RedirectResponse
    {
        return to_route('auth.admin.verify')->with('error',
            Lang::get('auth.invalid_otp_code'),
        );
    }

    private function handleUnexpectedError(): RedirectResponse
    {
        return back()->withErrors([
            'message' => Lang::get('auth.unexpected_error'),
        ]);
    }
}
