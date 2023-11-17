<?php

namespace Modules\Factor\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Factor\database\factories\TransactionCategoryFactory;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionCategoryFactory::new()
            ->count(20)
            ->create();
    }
}
