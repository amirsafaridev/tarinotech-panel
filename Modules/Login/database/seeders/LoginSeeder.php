<?php

namespace Modules\Login\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Login\database\factories\LoginFactory;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoginFactory::new()->count(50)->create();
    }
}
