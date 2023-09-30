<?php

namespace Database\Seeders;

use App\Service\PermissionService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            LoginSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
        ]);

        resolve(PermissionService::class)->sync();
    }
}
