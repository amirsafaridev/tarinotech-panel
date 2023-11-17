<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\factories\CompanyFactory;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyFactory::new()->count(20)->create();
    }
}
