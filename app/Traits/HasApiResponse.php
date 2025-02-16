<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

trait HasApiResponse
{
    protected function successResponse(mixed $data = null, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failResponse(string $message, int $status, mixed $data = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function exceptionResponse(Exception $exception, string $message = null): JsonResponse
    {
        report($exception);

        if ($exception instanceof ModelNotFoundException) {
            return $this->failResponse('Model Not Found.', 404);
        }

        $responseData = [
            'success' => false,
            'data' => null,
            'message' => $message ?? 'خطایی رخ داده است!',
        ];

        if (config('app.env') === 'local' || config('app.debug')) {
            $responseData['exception'] = $exception->getMessage();
        }

        return response()->json($responseData, 500);
    }
}
