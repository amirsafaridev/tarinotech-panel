<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\Irnic;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(50)
            ->has(Address::factory()->count(1))
            ->has(Company::factory()->count(1))
            ->has(Irnic::factory()->count(1))
            ->create();
    }
}
