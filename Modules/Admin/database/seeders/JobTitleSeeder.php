<?php

namespace Modules\Admin\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\database\factories\JobTitleFactory;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobTitleFactory::new()->count(10)
            ->create();
    }
}
