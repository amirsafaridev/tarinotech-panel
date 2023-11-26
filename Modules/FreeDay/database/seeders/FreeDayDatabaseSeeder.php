<?php

namespace Modules\FreeDay\database\seeders;

use Illuminate\Database\Seeder;

class FreeDayDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            FreeDaySeeder::class,
        ]);
    }
}
