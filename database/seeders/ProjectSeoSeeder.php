<?php

namespace Database\Seeders;

use App\Enums\Database\Project\ProjectBase;
use App\Models\Project;
use App\Models\ProjectSeo;
use Illuminate\Database\Seeder;

class ProjectSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectSeo::factory(20)
            ->create()->each(function (ProjectSeo $projectSeo) {
                Project::factory(1)
                    ->create([
                        'type_id' => $projectSeo->id,
                        'type_type' => ProjectSeo::class,
                        'project_base_id' => ProjectBase::Seo,
                    ]);
            });
    }
}
