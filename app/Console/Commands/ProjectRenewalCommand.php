<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Factor\app\Traits\DeterminesBillingDetailsTrait;
use Modules\Package\app\Models\Package;
use Modules\Package\app\Models\PackagePrice;
use Modules\Project\app\Enums\RenewalStatus;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectFacility;
use Modules\Project\app\Models\ProjectFacilityRenewal;
use Modules\Project\app\Models\ProjectRenewal;
use Modules\Project\app\Models\ProjectWeb;
use RuntimeException;
use Throwable;

class ProjectRenewalCommand extends Command
{
    use DeterminesBillingDetailsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:renewal {project_id? : The ID of the specific project to renew}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle project renewals by creating renewal records and updating renewal dates. Optionally specify a project ID.';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        $this->info('Starting project renewal process...');

        try {
            $projectId = $this->argument('project_id');

            $query = Project::query()
                ->whereHasMorph('target', [ProjectWeb::class], function (Builder $query) {
                    $query->whereHas('package');
                })
                ->whereHas('user')
                ->with(['target.package', 'user', 'facilities'])
                ->whereNotNull('agreement_at');

            // If project_id is provided, filter for that specific project
            if ($projectId) {
                $query->where('id', $projectId);
            } else {
                $query->where('renewal_at', '<=', now())
                    ->whereNotNull('renewal_at');
            }

            $projects = $query->get();

            $projectCount = $projects->count();
            $this->info("Found $projectCount project(s) eligible for renewal.");

            if ($projectCount === 0) {
                $this->info('No projects require renewal. Exiting.');

                return;
            }

            foreach ($projects as $project) {
                try {
                    $this->processProjectRenewal($project);
                    $this->info("Successfully processed project ID: $project->id");
                } catch (Throwable $e) {
                    $this->error("Failed to process project ID: $project->id");
                    Log::channel('project-renewal')->error(
                        "Error processing project ID $project->id: {$e->getMessage()}",
                        ['stack' => $e->getTraceAsString()]
                    );
                }
            }

            $this->info('Project renewal process completed successfully.');
        } catch (Throwable $e) {
            $this->error('An error occurred during the renewal process.');
            Log::channel('project-renewal')->critical(
                "Command failed: {$e->getMessage()}",
                ['stack' => $e->getTraceAsString()]
            );
            throw $e;
        }
    }

    /**
     * Process renewal for a single project
     */
    private function processProjectRenewal(Project $project): void
    {
        $package = $this->getPackage($project);
        $packagePrice = $this->getCurrentPricePackage($package);
        $packageCalculatedPrice = $packagePrice->price * 0.20;

        DB::transaction(function () use ($project, $packageCalculatedPrice, $packagePrice) {
            $renewal = ProjectRenewal::query()->create([
                'project_id' => $project->id,
                'project_price' => $project->price,
                'package_price' => $packagePrice->price,
                'package_calculated_price' => $packageCalculatedPrice,
                'status' => RenewalStatus::Pending,
            ]);

            Log::channel('project-renewal')->info("Created renewal with ID: $renewal->id for project ID: $project->id");

            if ($project->facilities->isNotEmpty()) {
                $project->facilities->each(function ($facility) use ($renewal) {
                    $days = Carbon::parse($facility->pivot->renewal_at)->diffInDays(now());

                    ProjectFacilityRenewal::query()->create([
                        'project_renewal_id' => $renewal->id,
                        'facility_id' => $facility->id,
                        'days' => $days,
                    ]);

                    if (app()->isLocal()) {
                        ProjectFacility::query()
                            ->where('id', $facility->pivot->id)
                            ->update(['renewal_at' => now()]);
                    }

                });
            }

            // Update Project Renewal Date to one year from now
            if (app()->isLocal()) {
                $project->update(['renewal_at' => now()->addYear()]);
            }

            Log::channel('project-renewal')->info("Updated renewal date for project ID: $project->id to $project->renewal_at");
        });
    }

    /**
     * Get the package associated with the project
     */
    private function getPackage(Project $project): Package
    {
        if (! isset($project->target->package)) {
            throw new RuntimeException("Project ID $project->id does not have a package.");
        }

        return $project->target->package;
    }

    /**
     * Get the current price for the package
     */
    private function getCurrentPricePackage(Package $package): PackagePrice
    {
        $price = $package->getPriceForDate(now())->getPrice();

        return $price ?: throw new RuntimeException("No price found for package ID $package->id on the current date.");

    }
}
