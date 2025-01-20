<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Project\app\Models\Facility;
use Modules\Project\app\Models\ProjectFacility;
use Modules\Project\app\Models\ProjectOption;
use Modules\Project\app\Models\ProjectOptionSet;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $projectOptions = ProjectOption::all();

        // Bulk insert facilities
        Facility::insert($projectOptions->map(function ($option) {
            return [
                'id' => $option->id,
                'title' => $option->title,
                'created_at' => $option->created_at,
                'updated_at' => $option->updated_at,
                'base_id' => $option->base_id,
            ];
        })->toArray());

        // Get project option sets with non-null renewal dates
        $projectOptionSets = ProjectOptionSet::query()
            ->with('target.project')
            ->whereHas('target.project', function ($query) {
                $query->whereNotNull('renewal_at');
            })
            ->get();

        // Bulk insert project facilities
        ProjectFacility::insert($projectOptionSets->map(function (ProjectOptionSet $set) {
            return [
                'project_id' => $set->target->project->id,
                'facility_id' => $set->project_option_id,
                'renewal_at' => $set->target->project->renewal_at,
                'created_at' => $set->target->project->renewal_at,
                'updated_at' => $set->target->project->renewal_at,
            ];
        })->toArray());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
