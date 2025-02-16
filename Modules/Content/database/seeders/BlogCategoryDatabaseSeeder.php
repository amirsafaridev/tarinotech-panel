<?php

namespace Modules\Content\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Content\database\factories\BlogCategoryFactory;

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
