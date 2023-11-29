<?php

namespace Modules\Auth\app\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function redirect;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = 'admin'): mixed
    {
        if (Auth::guard($guard)->check()) {
            Auth::shouldUse($guard);

            return redirect()->route('admin.home');
        }

        return $next($request);
    }
}
