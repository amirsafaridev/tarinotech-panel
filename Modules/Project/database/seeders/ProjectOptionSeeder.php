<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\database\factories\ProjectOptionFactory;

class ProjectOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectOptionFactory::new()
            ->count(10)
            ->create();
    }
}
