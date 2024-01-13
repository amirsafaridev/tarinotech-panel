<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Models\Facility;

class AdditionalFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Facility::factory(20)->create();
    }
}
