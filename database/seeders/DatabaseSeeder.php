<?php

namespace Database\Seeders;

use App\Service\PermissionService;
use Illuminate\Database\Seeder;
use Modules\Admin\database\seeders\AdminDatabaseSeeder;
use Modules\Blog\database\seeders\BlogCategoryDatabaseSeeder;
use Modules\Blog\database\seeders\BlogDatabaseSeeder;
use Modules\Factor\database\seeders\FactorDatabaseSeeder;
use Modules\FreeDay\database\seeders\FreeDayDatabaseSeeder;
use Modules\Package\database\seeders\PackageDatabaseSeeder;
use Modules\Project\database\seeders\ProjectBaseSeeder;
use Modules\Project\database\seeders\ProjectDatabaseSeeder;
use Modules\Project\database\seeders\ProjectTypeSeeder;
use Modules\Setting\database\seeders\SettingDatabaseSeeder;
use Modules\Support\database\seeders\SupportDatabaseSeeder;
use Modules\Ticket\database\seeders\TicketDatabaseSeeder;
use Modules\User\database\seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminDatabaseSeeder::class, //DONE
            UserDatabaseSeeder::class, //DONE
            ProjectBaseSeeder::class, // DONE
            ProjectTypeSeeder::class, // DONE
            PackageDatabaseSeeder::class, //DONE
            ProjectDatabaseSeeder::class, //DONE
            SettingDatabaseSeeder::class, // DONE
            FactorDatabaseSeeder::class, //DONE
            SupportDatabaseSeeder::class, //DONE
            TicketDatabaseSeeder::class, //DONE
            AutoMessageSeeder::class,
            FreeDayDatabaseSeeder::class, // DONE
            BlogCategoryDatabaseSeeder::class, //DONE
            BlogDatabaseSeeder::class, //DONE
        ]);

        resolve(PermissionService::class)->sync();
    }
}
