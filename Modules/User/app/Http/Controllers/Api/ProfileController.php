<?php

namespace Modules\User\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Modules\Auth\app\Resources\UserResource;

class ProfileController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $user = auth()->user();

            return $this->successResponse(new UserResource($user));
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'با موفقیت خارج شدید!');
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
