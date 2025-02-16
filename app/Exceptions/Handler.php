<?php

namespace App\Exceptions;

use App\Traits\HasApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
    use HasApiResponse;

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

    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->expectsJson()) {
            if ($e instanceof AuthenticationException) {
                return $this->failResponse('Unauthenticated.', 401);
            }
            if ($e instanceof ModelNotFoundException) {
                return $this->failResponse('Model Not Found.', 404);
            }
            if ($e instanceof ThrottleRequestsException) {
                return $this->failResponse('Too Many Attempts.', 429);
            }
            if ($e instanceof NotFoundHttpException) {
                return $this->failResponse('Route or Url Not Found.', 404);
            }
            if ($e instanceof MissingScopeException) {
                return $this->failResponse('User can access.', 403);
            }
        }

        return parent::render($request, $e);
    }
}
