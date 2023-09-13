<?php

namespace App\Exceptions;

use App\Service\Response\ResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Laravel\Sanctum\Exceptions\MissingScopeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $ResponseService = resolve(ResponseService::class);
            if ($e instanceof AuthenticationException) {
                return $ResponseService::failure('Unauthenticated.', 401);
            }
            if ($e instanceof ModelNotFoundException) {
                return $ResponseService::failure('Model Not Found.', 404);
            }
            if ($e instanceof ThrottleRequestsException) {
                return $ResponseService::failure('Too Many Attempts.', 429);
            }
            if ($e instanceof NotFoundHttpException) {
                return $ResponseService::failure('Route or Url Not Found.', 404);
            }
            if ($e instanceof MissingScopeException) {
                return $ResponseService::failure('User can access.', 403);
            }
        }

        return parent::render($request, $e);
    }
}
