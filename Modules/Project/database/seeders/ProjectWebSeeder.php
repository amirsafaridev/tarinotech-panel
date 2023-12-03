<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectWeb;
use Modules\Project\database\factories\ProjectFactory;
use Modules\Project\database\factories\ProjectWebFactory;

class ProjectWebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectWebFactory::new()
            ->count(20)
            ->create()->each(function (ProjectWeb $projectWeb) {
                ProjectFactory::new()
                    ->count(1)
                    ->create([
                        'target_id' => $projectWeb->id,
                        'target_type' => ProjectWeb::class,
                        'base_id' => ProjectBase::Web,
                        'type_id' => rand(1, 7),
                    ]);
            });
    }
}
