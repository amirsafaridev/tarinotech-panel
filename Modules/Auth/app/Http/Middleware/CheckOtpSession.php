<?php

namespace Modules\Auth\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class CheckOtpSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Session::has(config('auth.otp_code_session_key'))) {
            return to_route('auth.admin.login');
        }

        return $next($request);
    }
}
