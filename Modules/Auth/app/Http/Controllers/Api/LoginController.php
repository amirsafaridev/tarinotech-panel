<?php

namespace Modules\Auth\app\Http\Controllers\Api;

use App\Domain\Jobs\OtpGenerateJob;
use App\Domain\Jobs\SendOtpJob;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use App\Traits\NormalizeMobileNumber;
use Exception;
use Modules\Auth\app\Http\Requests\Api\Auth\DevLoginRequest;
use Modules\Auth\app\Http\Requests\Api\Auth\LoginRequest;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\User\UserResource;

class LoginController extends Controller
{
    use HasApiResponse, NormalizeMobileNumber;

    public function index(LoginRequest $request, OtpGenerateJob $otpGenerateJob, SendOtpJob $sendOtpJob)
    {
        try {
            $identify = $request->input('identify');
            if (! $this->isEmail($identify)) {
                $identify = $this->normalizeMobileNumber($identify);
            }
            $user = $this->findUserByIdentify($identify);

            if (! $user) {
                return $this->failResponse('اطلاعات ارسال شده صحیح نیست', 400);
            }

            $otp = $otpGenerateJob->handle($user, $identify);

            if (app()->isProduction()) {
                $sendOtpJob->handle($user, $identify, $otp);
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
            $mobile = $request->input('mobile');

            // Normalize the mobile number if provided
            if (! empty($mobile) && ! $this->isEmail($mobile)) {
                $mobile = $this->normalizeMobileNumber($mobile);
            }

            $user = User::query()
                ->where('mobile', $mobile)
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
}
