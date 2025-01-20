<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Facility;
use Modules\Project\app\Models\Project;
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
            ->create()
            ->each(function (ProjectWeb $projectWeb) {
                // Create Project
                $project = ProjectFactory::new()
                    ->create([
                        'target_id' => $projectWeb->id,
                        'target_type' => ProjectWeb::class,
                        'base_id' => ProjectBase::Web,
                        'type_id' => rand(1, 7),
                    ]);

                $facilitiesCount = rand(3, 6);

                $facilities = Facility::query()
                    ->where('base_id', ProjectBase::Web)
                    ->inRandomOrder()
                    ->limit($facilitiesCount)
                    ->get();

                foreach ($facilities as $facility) {
                    $project->facilities()->attach($facility->id, [
                        'renewal_at' => $this->getRenewalDate(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    /**
     * Get renewal date with 20% probability of being a year ago
     */
    private function getRenewalDate(): ?Carbon
    {
        return now()->subYear();
    }
}
