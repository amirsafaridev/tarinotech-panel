<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\factories\CommunicationFactory;

class CommunicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommunicationFactory::new()->count(5)->create();

    }
}
