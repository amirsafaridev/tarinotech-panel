<?php

namespace Modules\Auth\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Auth\app\Http\Requests\Api\Auth\VerifyRequest;
use Modules\Auth\app\Models\Login;
use Modules\Auth\app\Models\OtpCode;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\User\UserResource;

use function now;

class VerifyController extends Controller
{
    use HasApiResponse;

    public function index(VerifyRequest $request)
    {

        try {
            $otpCode = $this->getOtpCode($request->input('identify'), $request->input('code'));

            if (! $otpCode) {
                return $this->failResponse('اطلاعات ارسال شده صحیح نیست', 400);
            }

            $user = $this->verifyUser($otpCode);
            $this->loginUser($user);
            $this->deleteOtpCode($otpCode);
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->successResponse(['user' => new UserResource($user), 'token' => $token], 'با موفقیت وارد شدید');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function getOtpCode(string $identify, string $code): ?OtpCode
    {
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
