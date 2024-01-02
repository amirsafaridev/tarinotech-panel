<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\database\factories\BusinessDomainFactory;

class BusinessDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessDomainFactory::new()
            ->count(10)
            ->create();
    }
}
