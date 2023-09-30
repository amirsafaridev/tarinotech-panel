<?php

namespace App\Http\Middleware;

use App\Enums\Database\User\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPresenterUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check the user type against the provided userType parameter
        if ($request->user()->user_type !== UserType::Presenter) {
            abort(404, 'User type is not Presenter');
        }

        return $next($request);
    }
}
