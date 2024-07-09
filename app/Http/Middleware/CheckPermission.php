<?php

/** @noinspection PhpUnreachableStatementInspection */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
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
            'ADMIN_ROLE_EDIT', // Must Delete
            'ADMIN_ROLE_UPDATE', // Must Delete
            'ADMIN_ADMIN_EDIT', // Must Delete
            'ADMIN_ADMIN_UPDATE', // Must Delete
            'ADMIN_PERMISSION_SYNC', // Must Delete
            'ADMIN_PERMISSION_INDEX', // Must Delete
        ];
        if (in_array($permission, $allowPermissions) || $request->user('admin')->hasPermissionTo($permission)) {
            return $next($request);
        }

        return $request->ajax() ? response('Unauthorized.', 401) : abort(401);
    }
}
