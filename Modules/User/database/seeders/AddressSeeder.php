<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\factories\AddressFactory;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AddressFactory::new()->count(20)->create();
    }
}
