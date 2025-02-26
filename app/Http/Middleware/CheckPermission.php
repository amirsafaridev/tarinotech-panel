<?php

/** @noinspection PhpUnreachableStatementInspection */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        $permission = str($request->route()->getName())
            ->upper()
            ->replace(['.', '-'], '_')
            ->toString();
        $allowPermissions = [
            'ADMIN_ADMIN_PROFILE_INDEX',
            'ADMIN_ADMIN_PROFILE_UPDATE',
            'ADMIN_ADMIN_PROFILE_PASSWORD',
            'ADMIN_ADMIN_PROFILE_PASSWORD_UPDATE',
            'ADMIN_ADMIN_PROFILE_LOGOUT',
            'ADMIN_ADMIN_PROFILE_DISABLE2AGOOGLE',
            'ADMIN_ADMIN_PROFILE_ENABLE2AGOOGLE',
        ];
        if (in_array($permission, $allowPermissions) || $request->user('admin')->hasPermissionTo($permission)) {
            return $next($request);
        }

        return $request->ajax() ? response('Unauthorized.', 401) : abort(401);
    }
}