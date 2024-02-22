<?php

namespace Modules\Stream\app\Http\Middleware;

use Auth;
use Cache;
use Closure;
use DB;
use Illuminate\Http\Request;

class StreamMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {

        // Attempt to authenticate using web guard first (session based)
        $webAuthenticated = Auth::guard('admin')->check();

        // If not authenticated via web, attempt Sanctum (token based) authentication
        $apiAuthenticated = $webAuthenticated || $this->checkApiUser($request);

        // If neither web nor API authentication is successful, abort with an unauthorized error
        if (! $webAuthenticated && ! $apiAuthenticated) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }

    private function checkApiUser(Request $request): bool
    {
        // Split the cookie value into ID and token parts
        [$id, $token] = explode('|', $request->cookie('auth_token'), 2);

        // Log the ID and token for debugging purposes
        logger($id);
        logger($token);
        logger(hash('sha256', $token));

        // Define a cache key unique to this ID and token
        $cacheKey = 'api_user_check:'.hash('sha256', $id.'|'.$token);

        // Check if the validation result is already cached
        if (Cache::has($cacheKey)) {
            // Return the cached result
            return Cache::get($cacheKey);
        }

        // Perform the database query to validate the token and ID
        $isValid = DB::table('personal_access_tokens')
            ->where([
                ['token', '=', hash('sha256', $token)],
            ])
            ->exists();

        // Cache the validation result for 10 minutes if valid
        if ($isValid) {
            Cache::put($cacheKey, true, 600); // 600 seconds = 10 minutes
        }

        return $isValid;
    }
}
