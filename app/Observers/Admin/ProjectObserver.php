<?php

namespace App\Observers\Admin;

use App\Enums\Database\Factor\FactorStatus;
use App\Enums\Database\Project\ProjectBase;
use App\Models\Project;
use Modules\Factor\app\Models\Factor;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        if ($project->base_id === ProjectBase::Web) {
            $this->createWebFactor($project);
        }
    }

    private function createWebFactor(Project $project)
    {
        $taxRate = config('factor.tax');
        $originalPrice = $project->price;

        $factorDetails = [
            ['title' => 'بیعانه', 'percentage' => 30],
            ['title' => 'واریزی مرحله اول', 'percentage' => 40],
            ['title' => 'تسویه', 'percentage' => 30],
        ];

        foreach ($factorDetails as $factorDetail) {
            $factorTitle = $factorDetail['title'];
            $factorPercentage = $factorDetail['percentage'];

            $price = calcPercentOfPrice($factorPercentage, $originalPrice);
            $taxAmount = calcPercentOfPrice($taxRate, $price);
            $totalPrice = $price + $taxAmount;

            $factor = Factor::query()->create([
                'title' => $factorTitle,
                'admin_id' => $project->admin_id,
                'project_id' => $project->id,
                'final_price' => $totalPrice,
                'status' => FactorStatus::Draft,
                'is_official' => 0,
                'gateway_data' => [],
            ]);

            $factor->items()->create([
                'factor_id' => $factor->id,
                'title' => $factorTitle,
                'transaction_category_id' => 1,
                'price' => $price,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'discount' => 0,
                'final_price' => $totalPrice,
            ]);
        }
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
