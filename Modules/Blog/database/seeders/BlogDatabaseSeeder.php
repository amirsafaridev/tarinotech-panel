<?php

namespace Modules\Blog\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\database\factories\BlogFactoryFactory;

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
