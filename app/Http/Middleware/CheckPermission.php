<?php

/** @noinspection PhpUnreachableStatementInspection */

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Stringable;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        if (! App::isLocal()) {
            return $next($request);
        }
        $permission = $this->getName();
        $allowPermissions = [
            'permission_sync',
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
