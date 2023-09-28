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
            ProjectSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,
            CompanySeeder::class,
            IrnicSeeder::class,
        ]);

        resolve(PermissionService::class)->sync();
    }
}
