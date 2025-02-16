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
            BusinessDomainSeeder::class,
            FacilitySeeder::class,
            ProjectStatusSeeder::class,
            ProjectWebSeeder::class,
            ProjectSeoSeeder::class,
            ProjectAdsSeeder::class,
        ]);
    }
}
