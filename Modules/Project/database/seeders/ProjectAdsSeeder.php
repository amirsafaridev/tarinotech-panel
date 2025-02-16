<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\database\factories\ProjectAdsFactory;
use Modules\Project\database\factories\ProjectFactory;

class ProjectAdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectAdsFactory::new()->count(20)
            ->create()->each(function (ProjectAds $projectAds) {
                ProjectFactory::new()->count(1)
                    ->create([
                        'target_id' => $projectAds->id,
                        'target_type' => ProjectAds::class,
                        'base_id' => ProjectBase::Ads,
                        'type_id' => rand(1, 7),
                    ]);
            });
    }
}
