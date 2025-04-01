<?php

namespace Modules\Auth\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use App\Traits\NormalizeMobileNumber;
use Auth;
use Exception;
use Modules\Auth\app\Http\Requests\Api\Auth\VerifyRequest;
use Modules\Auth\app\Models\Login;
use Modules\Auth\app\Models\OtpCode;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\User\UserResource;

class VerifyController extends Controller
{
    use HasApiResponse, NormalizeMobileNumber;

    public function index(VerifyRequest $request)
    {
        try {
            $identify = $request->input('identify');

            // Normalize mobile number if it's not an email
            if (! $this->isEmail($identify)) {
                $identify = $this->normalizeMobileNumber($identify);
            }

            $otpCode = $this->getOtpCode($identify, $request->input('code'));

            if (! $otpCode) {
                return $this->failResponse('اطلاعات ارسال شده صحیح نیست', 400);
            }

            $user = $this->verifyUser($otpCode);
            $this->loginUser($user);
            $this->deleteOtpCode($otpCode);

            Auth::login($user);

            return $this->successResponse(['user' => new UserResource($user), 'token' => ''], 'با موفقیت وارد شدید');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function getOtpCode(string $identify, string $code): ?OtpCode
    {
        if (app()->isLocal()) {
            $code = config('auth.development_otp');
        }

        return OtpCode::query()
            ->with('user')
            ->where('identify', $identify)
            ->where('code', $code)
            ->whereDate('expired_at', '<=', now())
            ->first();
    }

    private function verifyUser(OtpCode $otpCode): User
    {
        $otpCode->user()->update(['verify_at' => now()]);

        return $otpCode->user;
    }

    private function loginUser($user): void
    {
        Login::userLogin($user);
    }

    private function deleteOtpCode($otpCode): void
    {
        $otpCode->delete();
    }
}
