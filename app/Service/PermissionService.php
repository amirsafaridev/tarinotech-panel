<?php

namespace App\Service;

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function sync(): int
    {
        $routes = Route::getRoutes();
        $prefix = 'admin.';

        $currentPermissions = Permission::get();
        $countCreated = 0;
        foreach ($routes as $route) {
            $name = $route->getName();

            if (! $name || ! str_starts_with($name, $prefix)) {
                continue;
            }
            $permissionName = $this->generatePermissionName($name);
            $checkExist = $currentPermissions->where('name', $permissionName)->first();
            if (! $checkExist) {
                Permission::query()->create([
                    'name' => $permissionName,
                    'title' => $permissionName,
                ]);
                $countCreated++;
            }
        }

        return $countCreated;
    }

    private function generatePermissionName($routeName): string
    {
        return strtoupper(str_replace(['.', '-'], ['_'], $routeName));
    }
}
