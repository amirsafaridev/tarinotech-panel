<?php

/** @noinspection PhpUnreachableStatementInspection */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Stringable;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {

        return $next($request);

        $permission = str($this->getName())->upper()->prepend('ADMIN_')->toString();

        $allowPermissions = [
            'permission_sync',
            'ADMIN_ADMIN_PROFILE_INDEX',
            'ADMIN_ADMIN_PROFILE_UPDATE',
            'ADMIN_ADMIN_PROFILE_PASSWORD',
            'ADMIN_ADMIN_PROFILE_PASSWORD_UPDATE',
            'ADMIN_ADMIN_PROFILE_LOGOUT',
        ];
        if (in_array($permission, $allowPermissions) || $request->user('admin')->hasPermissionTo($permission)) {
            return $next($request);
        }

        return $request->ajax() ? response('Unauthorized.', 401) : abort(401);
    }

    // Controller Method Detector
    private function getName(): string
    {
        $action = request()->route()->getAction()['controller'];

        return str($action)
            ->whenContains('Website', function (Stringable $s) {
                return $s->replace('Website\\', 'Website');
            })
            ->afterLast('\\')
            ->snake()
            ->replace('_controller', '')
            ->replace('@', '_')
            ->lower()
            ->replace(['_data', '_update', '_store'], ['_index', '_edit', '_create'])
            ->toString();
    }
}
