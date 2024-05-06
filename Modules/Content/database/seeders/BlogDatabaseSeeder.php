<?php

namespace Modules\Content\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Content\database\factories\BlogFactoryFactory;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogFactoryFactory::new()
            ->count(100)
            ->create();
    }
}
