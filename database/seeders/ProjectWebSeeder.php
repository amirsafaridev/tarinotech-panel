<?php

namespace Database\Seeders;

use App\Enums\Database\Project\ProjectBase;
use App\Models\Project;
use App\Models\ProjectWeb;
use Illuminate\Database\Seeder;

class ProjectWebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectWeb::factory(20)
            ->create()->each(function (ProjectWeb $projectWeb) {
                Project::factory(1)
                    ->create([
                        'type_id' => $projectWeb->id,
                        'type_type' => ProjectWeb::class,
                        'project_base_id' => ProjectBase::Web,
                    ]);
            });
    }
}
