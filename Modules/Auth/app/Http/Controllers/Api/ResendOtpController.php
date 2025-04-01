<?php

namespace Modules\Auth\app\Http\Controllers\Api;

use App\Domain\Jobs\OtpGenerateJob;
use App\Domain\Jobs\SendOtpJob;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use App\Traits\NormalizeMobileNumber;
use Exception;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Auth\app\Http\Requests\Api\Auth\ResendOtpRequest;
use Modules\Auth\app\Models\OtpCode;
use Modules\User\app\Models\User;

class ResendOtpController extends Controller
{
    use HasApiResponse, NormalizeMobileNumber;

    public function index(ResendOtpRequest $request, OtpGenerateJob $otpGenerateJob, SendOtpJob $sendOtpJob)
    {
        try {
            $identify = $request->input('identify');

            // Normalize mobile number if it's not an email
            if (! $this->isEmail($identify)) {
                $identify = $this->normalizeMobileNumber($identify);
            }

            $rateLimitResult = $this->checkRateLimit($request->ip());
            if ($rateLimitResult !== true) {
                return $rateLimitResult;
            }

            $user = $this->getUserByIdentify($identify);

            if (! $user) {
                return $this->failResponse('کاربری با این مشخصات یافت نشد', 404);
            }

            $lastOtp = OtpCode::query()
                ->where('identify', $identify)
                ->where('created_at', '>=', now()->subMinutes(2))
                ->first();

            if ($lastOtp) {
                $secondsLeft = now()->diffInSeconds($lastOtp->created_at->addMinutes(2));

                return $this->failResponse("لطفاً {$secondsLeft} ثانیه دیگر برای ارسال مجدد کد تایید صبر کنید", 400);
            }

            $this->deleteExistingOtpCodes($identify);

            $otp = $otpGenerateJob->handle($user, $identify);

            if (app()->isProduction()) {
                $sendOtpJob->handle($user, $identify, $otp);
            }

            return $this->successResponse([], 'کد تایید جدید ارسال شد');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function getUserByIdentify(string $identify): ?User
    {
        if ($this->isEmail($identify)) {
            return User::query()->where('email', $identify)->first();
        } else {
            return User::query()->where('mobile', $identify)->first();
        }
    }

    private function deleteExistingOtpCodes(string $identify): void
    {
        OtpCode::query()->where('identify', $identify)->delete();
    }

    private function checkRateLimit(string $ip)
    {
        if (RateLimiter::tooManyAttempts("resend-otp:{$ip}", 3)) {
            $seconds = RateLimiter::availableIn("resend-otp:{$ip}");

            return $this->failResponse("تعداد درخواست‌های شما بیش از حد مجاز است. لطفاً {$seconds} ثانیه دیگر تلاش کنید", 429);
        }
        RateLimiter::hit("resend-otp:{$ip}");

        return true;
    }
}
