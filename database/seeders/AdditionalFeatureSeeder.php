<?php

namespace Database\Seeders;

use App\Models\AdditionalFeature;
use Illuminate\Database\Seeder;

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

        AdditionalFeature::factory(20)->create();
    }
}
