<?php

namespace Modules\Admin\database\seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            JobTitleSeeder::class,
            AdminSeeder::class,
            LeaveSeeder::class,
        ]);
    }
} 