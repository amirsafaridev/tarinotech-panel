<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\database\factories\ProjectFactory;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectFactory::new()
            ->count(50)
            ->create();
    }
}
