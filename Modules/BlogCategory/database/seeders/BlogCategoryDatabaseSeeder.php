<?php

namespace Modules\BlogCategory\database\seeders;

use Illuminate\Database\Seeder;
use Modules\BlogCategory\database\factories\BlogCategoryFactory;

class BlogCategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCategoryFactory::new()
            ->count(10)
            ->create();
    }
}
