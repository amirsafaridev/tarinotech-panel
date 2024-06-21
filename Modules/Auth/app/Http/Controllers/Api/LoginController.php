<?php

namespace Modules\Auth\app\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Notifications\User\Auth\OtpNotification;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Auth\app\Http\Requests\Api\Auth\DevLoginRequest;
use Modules\Auth\app\Http\Requests\Api\Auth\LoginRequest;
use Modules\Auth\app\Models\OtpCode;
use Modules\Auth\app\Notifications\SendCodeNotification;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\User\UserResource;

class LoginController extends Controller
{
    use HasApiResponse;

    public function index(LoginRequest $request)
    {

        try {
            $identify = $request->input('identify');
            $user = $this->findUserByIdentify($identify);

            if (! $user) {
                return $this->failResponse('اطلاعات ارسال شده صحیح نیست', 400);
            }

            $otp = $this->generateOtp($user, $identify, $request);

            if (app()->isProduction()) {
                $this->sendOtp($user, $identify, $otp);
            }

            return $this->successResponse(['code' => $otp], 'کد برای شما ارسال شد');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }

    }

    public function devLogin(DevLoginRequest $request)
    {
        $this->dieInProduction();
        try {

            $user = User::query()
                ->where('mobile', $request->input('mobile'))
                ->with('projects')
                ->firstOrFail();

            $token = $user->createToken('authToken')->plainTextToken;

            return $this->successResponse(['user' => new UserResource($user), 'token' => $token], 'با موفقیت وارد شدید');

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function findUserByIdentify($identify): ?User
    {
        return User::query()
            ->where('is_block', false)
            ->where(function ($query) use ($identify) {
                $query->where('mobile', $identify)
                    ->orWhere('email', $identify);
            })
            ->first();
    }

    private function generateOtp($user, $identify, $request): string
    {
        $otp = Helper::randNumeric(4);

        if (app()->isLocal()) {
            $otp = config('auth.development_otp');
        }

        OtpCode::query()->create([
            'identify' => $identify,
            'user_type' => User::class,
            'user_id' => $user->id,
            'code' => $otp,
            'expired_at' => now()->addMinutes(5),
            'ip' => $request->ip(),
            'agent' => $request->userAgent(),
        ]);

        return $otp;
    }

    private function sendOtp(User $user, string $identify, string $otp): void
    {
        if (filter_var($identify, FILTER_VALIDATE_EMAIL)) {
            $user->notify(new SendCodeNotification($otp));
        } else {
            $user->notify(new OtpNotification($otp));
        }
    }
}
