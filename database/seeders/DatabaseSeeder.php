<?php

namespace Database\Seeders;

use App\Service\PermissionService;
use Illuminate\Database\Seeder;
use Modules\Admin\database\seeders\AdminDatabaseSeeder;
use Modules\Blog\database\seeders\BlogDatabaseSeeder;
use Modules\BlogCategory\database\seeders\BlogCategoryDatabaseSeeder;
use Modules\Factor\database\seeders\FactorDatabaseSeeder;
use Modules\FreeDay\database\seeders\FreeDayDatabaseSeeder;
use Modules\User\database\seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //RoleDatabaseSeeder::class, //DONE
            AdminDatabaseSeeder::class, //DONE
            UserDatabaseSeeder::class, //DONE
            PackageSeeder::class,
            ProjectBaseSeeder::class,
            ProjectTypeSeeder::class,
            ProjectStatusSeeder::class,
            ProjectWebSeeder::class,
            ProjectSeoSeeder::class,
            ProjectAdsSeeder::class,
            SettingSeeder::class,
            AdditionalFeatureSeeder::class,
            FactorDatabaseSeeder::class, //DONE
            SampleMessageSeeder::class,
            AutoMessageSeeder::class,
            FreeDayDatabaseSeeder::class, // DONE
            BlogCategoryDatabaseSeeder::class, //DONE
            BlogDatabaseSeeder::class, //DONE
        ]);

        resolve(PermissionService::class)->sync();
    }
}
