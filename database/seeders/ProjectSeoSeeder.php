<?php

namespace Database\Seeders;

use App\Enums\Database\Project\ProjectBase;
use App\Models\Project;
use App\Models\ProjectSeo;
use App\Models\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

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

        ProjectSeo::factory(20)
            ->create()->each(function (ProjectSeo $projectSeo) use ($statuses) {
                Project::factory(1)
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
