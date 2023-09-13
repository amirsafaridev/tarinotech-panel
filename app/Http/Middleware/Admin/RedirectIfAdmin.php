<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->check()) {
            Auth::shouldUse($guard);

            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
