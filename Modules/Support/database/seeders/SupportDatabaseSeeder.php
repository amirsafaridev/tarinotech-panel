<?php

namespace Modules\Support\database\seeders;

use Illuminate\Database\Seeder;

class SupportDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            SampleMessageSeeder::class,
        ]);
    }
}
