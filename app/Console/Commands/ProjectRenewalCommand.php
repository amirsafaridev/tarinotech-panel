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
    protected $signature = 'project:renewal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle project renewals by creating renewal records and updating renewal dates';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting project renewal process...');

        try {
            // Fetch projects eligible for renewal
            $projects = Project::query()
                ->where('renewal_at', '<=', now())
                ->whereHasMorph('target', [ProjectWeb::class], function (Builder $query) {
                    $query->whereHas('package');
                })
                ->whereHas('user')
                ->with(['target.package', 'user', 'facilities'])
                ->whereNotNull('renewal_at')
                ->whereNotNull('agreement_at')
                ->get();

            $projectCount = $projects->count();
            $this->info("Found {$projectCount} project(s) eligible for renewal.");

            if ($projectCount === 0) {
                $this->info('No projects require renewal. Exiting.');

                return;
            }

            foreach ($projects as $project) {
                try {
                    $this->processProjectRenewal($project);
                } catch (Throwable $e) {
                    $this->error("Failed to process project ID: {$project->id}");
                    Log::channel('project-renewal')->error(
                        "Error processing project ID {$project->id}: {$e->getMessage()}",
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
        }
    }

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

            Log::channel('project-renewal')->info("Created renewal with ID: {$renewal->id} for project ID: {$project->id}");

            if ($project->facilities->isNotEmpty()) {
                $project->facilities->each(function ($facility) use ($renewal) {

                    $days = Carbon::parse($facility->pivot->renewal_at)->diffInDays(now());

                    ProjectFacilityRenewal::query()->create([
                        'project_renewal_id' => $renewal->id,
                        'facility_id' => $facility->id,
                        'days' => $days,
                    ]);

                    ProjectFacility::query()
                        ->where('id', $facility->pivot->id)
                        ->update(['renewal_at' => now()]);
                });

            }

            // Update Project Renewal Date to one year from now
            // $project->update(['renewal_at' => now()->addYear()]);

            // Log the updated renewal date
            Log::channel('project-renewal')->info("Updated renewal date for project ID: {$project->id} to {$project->renewal_at}");
        });
    }

    private function getPackage(Project $project): Package
    {
        if (! isset($project->target->package)) {
            throw new RuntimeException("Project ID {$project->id} does not have a package.");
        }

        return $project->target->package;
    }

    private function getCurrentPricePackage(Package $package): PackagePrice
    {
        $price = $package->getPriceForDate(now())->getPrice();

        if (! $price) {
            throw new RuntimeException("No price found for package ID {$package->id} on the current date.");
        }

        return $price;
    }
}
