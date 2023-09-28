<?php

namespace Database\Seeders;

use App\Models\Irnic;
use Illuminate\Database\Seeder;

class IrnicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Irnic::factory(20)->create();
    }
}
