<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait HasJsonCommonResponse
{
    protected function successResponse(): JsonResponse
    {
        return response()->json([
            'result' => 'success',
            'message' => trans('panel.success_store'),
        ]);
    }

    protected function successUpdateResponse(): JsonResponse
    {
        return response()->json([
            'result' => 'success',
            'message' => trans('panel.success_update'),
        ]);
    }

    protected function exceptionResponse(Exception $exception): JsonResponse
    {
        report($exception);

        return response()->json([
            'result' => 'exception',
            'message' => trans('panel.error_store'),
        ], 500);
    }

    protected function successBack(): RedirectResponse
    {
        return back()->with('success', trans('panel.error_delete'));
    }

    protected function errorBack(): RedirectResponse
    {
        return back()->with('danger', trans('panel.error_delete'));
    }

    protected function exceptionBack(Exception $exception): RedirectResponse
    {
        report($exception);

        return back()->with('danger', trans('panel.error_delete'));
    }
}
