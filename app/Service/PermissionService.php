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

        $currentPermissions = Permission::pluck('name');

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            if ($name && str_starts_with($name, $prefix) && ! $currentPermissions->contains($this->generatePermissionName($name))) {
                $this->createPermission($this->generatePermissionName($name));
                $countCreated++;
            }
        }

        foreach ($this->additionalPermissions as $additionalPermission) {
            $permissionName = $this->generatePermissionName($additionalPermission);

            if (! $currentPermissions->contains($permissionName)) {
                $this->createPermission($permissionName);
                $countCreated++;
            }
        }

        return $countCreated;
    }

    private function createPermission(string $permissionName): void
    {
        Permission::create([
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
