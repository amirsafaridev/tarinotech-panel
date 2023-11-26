<?php

namespace Database\Seeders;

use App\Enums\Database\Project\ProjectBase;
use App\Models\Project;
use App\Models\ProjectAds;
use Illuminate\Database\Seeder;

class ProjectAdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectAds::factory(20)
            ->create()->each(function (ProjectAds $projectAds) {
                Project::factory(1)
                    ->create([
                        'target_id' => $projectAds->id,
                        'target_type' => ProjectAds::class,
                        'base_id' => ProjectBase::Ads,
                        'type_id' => rand(1, 7),
                    ]);
            });
    }
}
