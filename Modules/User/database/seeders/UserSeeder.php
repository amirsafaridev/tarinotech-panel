<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\factories\AddressFactory;
use Modules\User\database\factories\CompanyFactory;
use Modules\User\database\factories\IrnicFactory;
use Modules\User\database\factories\UserFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserFactory::new()->count(1)
            ->has(AddressFactory::new()->count(1))
            ->has(CompanyFactory::new()->count(1))
            ->has(IrnicFactory::new()->count(1))
            ->create();
    }
}
