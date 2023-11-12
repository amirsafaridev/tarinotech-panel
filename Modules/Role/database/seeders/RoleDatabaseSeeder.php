<?php

namespace Modules\Role\database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'مدیریت',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'کارشناس فروش',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'مدیر پروژه',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'کارشناس طراحی',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'پشتیبان وب سایت',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
        ];

        Role::query()->insert($roles);
    }
}
