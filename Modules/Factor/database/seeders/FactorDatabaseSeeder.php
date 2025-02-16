<?php

namespace Modules\Factor\database\seeders;

use Illuminate\Database\Seeder;

class FactorDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TransactionCategorySeeder::class,
        ]);
    }
}
