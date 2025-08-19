<?php

namespace App\Domain\Jobs;

use Modules\Admin\app\Models\Admin;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\TransactionCategory;
use Modules\Project\app\Models\Project;
use Modules\User\app\Enums\PersonType;

class WebProjectFactorMakerJob
{
    public function handle(Project $project)
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
            $taxAmount = $price * $taxRate;
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
                'status' => FactorStatus::Pending,
                'is_official' => $isOfficial,
                'is_automate' => true,
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
    public function handleFacilitiesFactore(Project $project,  $userId, $facility)
    {

        $taxRate = config('factor.tax');



        $factorTitle = $facility['title'];
        $categoryId = 2;

        $price = ($facility['customer_extra_unit'] ?? 0) * 25;
        $taxAmount = $price * $taxRate;
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
            'admin_id' => $userId,
            'project_id' => $project->id,
            'facility_id' => $facility['id'],
            'final_price' => $totalPrice,
            'status' => FactorStatus::Pending,
            'is_official' => $isOfficial,
            'is_automate' => true,
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
