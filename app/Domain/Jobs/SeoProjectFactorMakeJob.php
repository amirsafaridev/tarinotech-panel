<?php

namespace App\Domain\Jobs;

use Exception;
use Hekmatinasser\Verta\Verta;
use Modules\Admin\app\Models\Admin;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\ProjectSeo;
use Modules\User\app\Enums\PersonType;

class SeoProjectFactorMakeJob
{
    protected array $monthNames = [
        1 => 'اول',
        2 => 'دوم',
        3 => 'سوم',
        4 => 'چهارم',
        5 => 'پنجم',
        6 => 'ششم',
        7 => 'هفتم',
        8 => 'هشتم',
        9 => 'نهم',
        10 => 'دهم',
        11 => 'یازدهم',
        12 => 'دوازدهم',
    ];

    public function handle(ProjectSeo $projectSeo)
    {
        try {
            $taxRate = config('factor.tax');
            $agreementDuration = $this->calculateAgreementDuration($projectSeo->agreement_duration);
            $monthlyPrice = $this->calculateMonthlyPrice($projectSeo->project->price, $agreementDuration);

            $jalaliDate = Verta::instance($projectSeo->project->agreement_at);
            $accumulatedTotal = 0;

            for ($month = 1; $month <= $agreementDuration; $month++) {
                $price = $this->calculatePrice($month, $agreementDuration, $monthlyPrice, $projectSeo->project->price, $accumulatedTotal);
                $taxAmount = $this->calculateTaxAmount($price, $taxRate);
                $totalPrice = $this->calculateTotalPrice($price, $taxAmount);

                $factorTitle = $this->getJalaliFormattedDate($jalaliDate, $month);

                $this->createFactor($projectSeo, $jalaliDate, $factorTitle, $totalPrice, $taxRate, $price, $taxAmount, $month);

                $accumulatedTotal += $price;
                $jalaliDate = $jalaliDate->addMonth();
            }
        } catch (Exception $e) {
            report($e);
        }
    }
    public function handleFacilitiesFactore(ProjectSeo $projectSeo, $userId, $facility)
    {
        try {
            $taxRate = config('factor.tax');
            $agreementDuration = $this->calculateAgreementDuration($projectSeo->agreement_duration);
            $monthlyPrice = $this->calculateMonthlyPrice($projectSeo->project->price, $agreementDuration);

            $jalaliDate = Verta::instance($projectSeo->project->agreement_at);
            $accumulatedTotal = 0;

                $taxAmount = 0;
                $totalPrice =  ($facility['customer_extra_unit'] ?? 0) * 25000;

                $factorTitle = $facility['title'];
                $factor = Factor::query()->create([
                    'title' => $factorTitle,
                    'admin_id' => $userId,
                    'project_id' => $projectSeo->project->id,
                    'facility_id' => $facility['id'],
                    'final_price' => $totalPrice,
                    'status' => FactorStatus::Pending,
                    'is_official' => $this->determineIsOfficial($projectSeo->project->user),
                    'is_automate' => true,
                    'gateway' => $this->determinePaymentGateway($projectSeo->project->user),
                    'gateway_data' => [],
                    'created_at' => $jalaliDate->datetime(),
                    'updated_at' => $jalaliDate->datetime(),
                ]);

                $factor->items()->create([
                    'factor_id' => $factor->id,
                    'title' => $factorTitle,
                    'transaction_category_id' => 6,
                    'price' => $totalPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'discount' => 0,
                    'final_price' => $totalPrice,
                    'created_at' => $jalaliDate->datetime(),
                    'updated_at' => $jalaliDate->datetime(),
                ]);


                $jalaliDate = $jalaliDate->addMonth();
            
        } catch (Exception $e) {
            report($e);
        }
    }

    protected function calculateAgreementDuration(int $agreementDurationInDays): int
    {
        return round($agreementDurationInDays / 30);
    }

    protected function calculateMonthlyPrice(float $totalPrice, int $durationInMonths): float
    {
        return $totalPrice / $durationInMonths;
    }

    protected function calculatePrice(int $currentMonth, int $agreementDuration, float $monthlyPrice, float $originalPrice, float $accumulatedTotal): float
    {
        return ($currentMonth == $agreementDuration) ? $originalPrice - $accumulatedTotal : round($monthlyPrice, 2);
    }

    protected function calculateTaxAmount(float $price, float $taxRate): float
    {
        return $price * $taxRate;
    }

    protected function calculateTotalPrice(float $price, float $taxAmount): float
    {
        return $price + $taxAmount;
    }

    /**
     * @throws Exception
     */
    protected function createFactor(ProjectSeo $projectSeo, Verta $jalaliDate, string $factorTitle, float $totalPrice, float $taxRate, float $price, float $taxAmount, int $month): void
    {
        $factor = Factor::query()->create([
            'title' => $factorTitle,
            'admin_id' => $projectSeo->project->admin_id,
            'project_id' => $projectSeo->project->id,
            'final_price' => $totalPrice,
            'status' => FactorStatus::Pending,
            'is_official' => $this->determineIsOfficial($projectSeo->project->user),
            'is_automate' => true,
            'gateway' => $this->determinePaymentGateway($projectSeo->project->user),
            'gateway_data' => [],
            'created_at' => $jalaliDate->datetime(),
            'updated_at' => $jalaliDate->datetime(),
        ]);

        $factor->items()->create([
            'factor_id' => $factor->id,
            'title' => $factorTitle,
            'transaction_category_id' => 6,
            'price' => $price,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => 0,
            'final_price' => $totalPrice,
            'created_at' => $jalaliDate->datetime(),
            'updated_at' => $jalaliDate->datetime(),
        ]);
    }

    protected function determineIsOfficial($user): bool
    {
        return $user && ($user->person_type === PersonType::Legal || $user->official_bill);
    }

    protected function determinePaymentGateway($user): string
    {
        return $this->determineIsOfficial($user) ? PaymentGateway::SEPEHR : PaymentGateway::PAYPING;
    }

    protected function getJalaliFormattedDate(Verta $date, int $monthNumber): string
    {
        $monthName = $date->formatWord('F');

        return "سئو ماه {$this->monthNames[$monthNumber]} ({$monthName})";
    }
}
