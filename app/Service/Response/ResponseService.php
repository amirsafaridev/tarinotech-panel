<?php

namespace App\Service\Response;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class ResponseService
{
    public static function success($message, $data = [])
    {

        return Response::json([
            'status' => ResponseStatusCode::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function failure($message, $code, $data = [])
    {
        return Response::json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function token($user, $token)
    {
        return Response::json([
            'status' => 200,
            'message' => trans('api.auth.login.success'),
            'token' => $token,
            'user' => $user,
        ]);
    }
}
