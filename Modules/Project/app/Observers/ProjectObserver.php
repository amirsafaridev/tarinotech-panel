<?php

namespace Modules\Project\app\Observers;

use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\TransactionCategory;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Project;
use Modules\User\app\Enums\PersonType;

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

        $transactionCategories = TransactionCategory::query()
            ->get()
            ->keyBy('id')
            ->toArray();

        $factorDetails = [
            ['title' => $transactionCategories[2]['title'], 'percentage' => 30, 'category_id' => 2],
            ['title' => $transactionCategories[3]['title'], 'percentage' => 40, 'category_id' => 3],
            ['title' => $transactionCategories[4]['title'], 'percentage' => 30, 'category_id' => 4],
        ];

        foreach ($factorDetails as $factorDetail) {
            $factorTitle = $factorDetail['title'];
            $factorPercentage = $factorDetail['percentage'];
            $categoryId = $factorDetail['category_id'];

            $price = calcPercentOfPrice($factorPercentage, $originalPrice);
            $taxAmount = calcPercentOfPrice($taxRate, $price);
            $totalPrice = $price + $taxAmount;

            $isOfficial = false;
            $gateway = PaymentGateway::PAYPING;

            if ($user = $project->user) {
                if ($user->person_type === PersonType::Legal || $user->official_bill) {
                    $isOfficial = true;
                    $gateway = PaymentGateway::SEPEHR;
                }
            }

            $factor = Factor::query()->create([
                'title' => $factorTitle,
                'admin_id' => $project->admin_id,
                'project_id' => $project->id,
                'final_price' => $totalPrice,
                'status' => FactorStatus::Draft,
                'is_official' => $isOfficial,
                'gateway' => $gateway,
                'gateway_data' => [],
            ]);

            $factor->items()->create([
                'factor_id' => $factor->id,
                'title' => $factorTitle,
                'transaction_category_id' => $categoryId,
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
