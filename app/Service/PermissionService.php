<?php

namespace App\Service;

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public array $additionalPermissions = ['SUPER_ADMIN'];

    public function sync(): int
    {
        $prefix = 'admin.';
        $countCreated = 0;

        $routes = Route::getRoutes();
        $addableRoutes = [];
        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name && str($name)->startsWith($prefix)) {
                $addableRoutes[] = $route->getName();
            }
        }

        foreach ($addableRoutes as $routeName) {
            if ($this->checkPermissionExist($routeName)) {
                continue;
            }
            $this->createPermission($this->generatePermissionName($routeName));
            $countCreated++;
        }

        foreach ($this->additionalPermissions as $additionalPermission) {
            if ($this->checkPermissionExist($additionalPermission)) {
                continue;
            }
            $permissionName = $this->generatePermissionName($additionalPermission);
            $this->createPermission($permissionName);
            $countCreated++;
        }

        return $countCreated;
    }

    private function checkPermissionExist(string $name): bool
    {
        return Permission::query()
            ->where('name', $name)
            ->where('guard_name', 'admin')
            ->exists();
    }

    private function createPermission(string $permissionName): void
    {
        Permission::query()->create([
            'guard_name' => 'admin',
            'name' => $permissionName,
            'title' => $permissionName,
        ]);
    }

    private function generatePermissionName(string $routeName): string
    {
        return str_replace(['-', '.'], '_', strtoupper($routeName));
    }
}
