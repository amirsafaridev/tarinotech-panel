<?php

namespace Modules\Stream\app\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class StreamMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authenticated = Auth::check();
        if (! $authenticated) {
            $authenticated = Auth::guard('admin')->check();
        }

        if (! $authenticated) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
