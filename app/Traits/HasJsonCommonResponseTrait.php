<?php

namespace App\Traits;

use App;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait HasJsonCommonResponseTrait
{
    protected function successResponse(?string $backNav = null): JsonResponse
    {
        return response()->json([
            'back' => $backNav,
            'result' => 'success',
            'message' => trans('panel.success_store'),
        ]);
    }

    protected function successUpdateResponse(?string $backNav = null): JsonResponse
    {
        return response()->json([
            'back' => $backNav,
            'result' => 'success',
            'message' => trans('panel.success_update'),
        ]);
    }

    protected function failure(string $message, int $code): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'message' => $message,
        ], $code);
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

    protected function successBack($route, $message): RedirectResponse
    {
        return redirect($route)
            ->with('success', $message);
    }

    protected function errorBack(string $message, ?string $route = null): RedirectResponse
    {
        return redirect($route)->with('danger', $message);
    }

    protected function exceptionBack(Exception $exception): RedirectResponse
    {
        report($exception);

        if ($exception instanceof QueryException && $exception->getCode() == '23000') {
            $tableName = $this->extractTableName($exception->errorInfo);

            return back()->with('danger', trans('panel.error_relation_constraint', ['table' => $tableName]));
        }

        return back()->with('danger', trans('panel.error_exception'));
    }

    protected function extractTableName(array $errorInfo): string
    {
        $tableName = 'unknown table';

        if (isset($errorInfo[2])) {
            preg_match('/FOREIGN KEY \(`.*`\) REFERENCES `(.*)` \(`.*`\)/', $errorInfo[2], $matches);
            if (isset($matches[1])) {
                $tableName = $matches[1];
            }
        }

        return $tableName;
    }
}
