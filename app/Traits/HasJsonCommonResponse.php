<?php

namespace App\Traits;

use App;
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

        $message = trans('panel.error');
        if (App::isLocal() || App::isProduction()) {
            $message = $exception->getMessage();
        }

        return response()->json([
            'result' => 'exception',
            'message' => $message,
        ], 500);
    }

    protected function successDestroyBack($route): RedirectResponse
    {
        return redirect($route)
            ->with('success', trans('panel.success_delete'));
    }

    protected function errorBack($message): RedirectResponse
    {
        return redirect()->with('danger', $message);
    }

    protected function exceptionBack(Exception $exception): RedirectResponse
    {
        report($exception);

        return back()->with('danger', trans('panel.error_exception'));
    }
}
