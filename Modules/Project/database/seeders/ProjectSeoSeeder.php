<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectStatus;
use Modules\Project\database\factories\ProjectFactory;
use Modules\Project\database\factories\ProjectSeoFactory;

class ProjectSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ProjectStatus::query()
            ->whereHas('type', function (Builder $q) {
                return $q->where('base_id', ProjectBase::Seo);
            })
            ->get();

        ProjectSeoFactory::new()
            ->count(20)
            ->create()->each(function (ProjectSeo $projectSeo) use ($statuses) {
                ProjectFactory::new()
                    ->count(1)
                    ->create([
                        'target_id' => $projectSeo->id,
                        'target_type' => ProjectSeo::class,
                        'base_id' => ProjectBase::Seo,
                        'type_id' => rand(1, 7),
                        'status_id' => $statuses->random()->id,
                    ]);
            });
    }
}
