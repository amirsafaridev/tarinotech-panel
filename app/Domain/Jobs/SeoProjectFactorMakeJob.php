<?php

namespace App\Domain\Jobs;

use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\ProjectSeo;
use Modules\User\app\Enums\PersonType;

class SeoProjectFactorMakeJob
{
    public function handle(ProjectSeo $projectSeo)
    {
        $taxRate = config('factor.tax');

        $agreementDuration = round($projectSeo->agreement_duration / 30);

        $project = $projectSeo->project;
        $originalPrice = $project->price;

        $monthlyPrice = $originalPrice / $agreementDuration;

        $data = $project->agreement_at;

        $accumulatedTotal = 0;

        for ($month = 1; $month <= $agreementDuration; $month++) {
            $factorTitle = $this->getJalaliFormattedDate($data);

            $categoryId = 6;

            if ($month == $agreementDuration) {
                $price = $originalPrice - $accumulatedTotal;
            } else {
                $price = round($monthlyPrice, 2);
            }

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

            $accumulatedTotal += $price;

            $data->addMonth();
        }
    }

    protected function getJalaliFormattedDate(mixed $data): string
    {
        return $data->toJalali()->format('d').' '.$data->toJalali()->formatWord('F');
    }
}
