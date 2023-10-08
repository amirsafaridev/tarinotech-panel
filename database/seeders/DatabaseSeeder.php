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
            ProjectTypeSeeder::class,
            ProjectStatusSeeder::class,
            ProjectSeeder::class,
            SettingSeeder::class,
            PackageSeeder::class,
            AdditionalFeatureSeeder::class,
            TransactionCategorySeeder::class,
            SampleMessageSeeder::class,
            AutoMessageSeeder::class,
            FreeDaySeeder::class,
            BlogCategorySeeder::class,
            BlogSeeder::class,
        ]);

        resolve(PermissionService::class)->sync();
    }
}
