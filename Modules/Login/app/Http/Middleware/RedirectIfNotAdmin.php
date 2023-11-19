<?php

namespace Modules\Login\app\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

use function route;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $guard
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, $guard = 'admin')
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
