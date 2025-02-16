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
            // Create Record By Migration
            // NotifySeeder::class,
            SampleMessageSeeder::class,
        ]);
    }
}
