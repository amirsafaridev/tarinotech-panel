<?php

namespace Database\Seeders;

use App\Service\PermissionService;
use Illuminate\Database\Seeder;
use Modules\Blog\database\seeders\BlogDatabaseSeeder;
use Modules\BlogCategory\database\seeders\BlogCategoryDatabaseSeeder;
use Modules\Factor\database\seeders\FactorDatabaseSeeder;
use Modules\Role\database\seeders\RoleDatabaseSeeder;
use Modules\User\database\seeders\UserDatabaseSeeder;

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
            UserDatabaseSeeder::class,
            PackageSeeder::class,
            ProjectBaseSeeder::class,
            ProjectTypeSeeder::class,
            ProjectStatusSeeder::class,
            ProjectWebSeeder::class,
            ProjectSeoSeeder::class,
            ProjectAdsSeeder::class,
            SettingSeeder::class,
            AdditionalFeatureSeeder::class,
            FactorDatabaseSeeder::class,
            SampleMessageSeeder::class,
            AutoMessageSeeder::class,
            FreeDaySeeder::class,
            BlogCategoryDatabaseSeeder::class,
            BlogDatabaseSeeder::class,
            RoleDatabaseSeeder::class,
        ]);

        resolve(PermissionService::class)->sync();
    }
}
