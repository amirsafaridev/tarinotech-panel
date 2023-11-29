<?php

namespace Modules\Auth\app\Http\Middleware\Admin;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function route;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next, string $guard = 'admin'): mixed
    {
        if (Auth::guard($guard)->check()) {
            Auth::shouldUse($guard);

            return $next($request);
        }

        $redirectToRoute = $request->expectsJson() ? '' : route('auth.admin.login');

        throw new AuthenticationException(
            'Unauthenticated.', [$guard], $redirectToRoute
        );
    }
}
