<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;

class ProjectDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ProjectBaseSeeder::class,
            ProjectTypeSeeder::class,
            ProjectStatusSeeder::class,
            ProjectWebSeeder::class,
            ProjectSeoSeeder::class,
            ProjectAdsSeeder::class,
        ]);
    }
}
