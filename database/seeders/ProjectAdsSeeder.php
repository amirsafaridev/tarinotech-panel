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
                        'type_id' => $projectAds->id,
                        'type_type' => ProjectAds::class,
                        'project_base_id' => ProjectBase::Ads,
                    ]);
            });
    }
}
