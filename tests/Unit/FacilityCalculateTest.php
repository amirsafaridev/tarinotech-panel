<?php

namespace Tests\Unit;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use App\Service\FacilityCalculate;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Project\app\Models\ProjectFacilityRenewal;
use Modules\Project\Database\Factories\ProjectFacilityRenewalFactory;
use Tests\TestCase;

class FacilityCalculateTest extends TestCase
{
    use RefreshDatabase;

    private function createFacilityRenewal(
        int $priceType,
        int $workCycle,
        int $financialCycle,
        int $priceValue = 1000,
        ?int $workCycleValue = null,
        ?int $financialCycleValue = null,
        ?int $days = null,
        ?Carbon $createdAt = null
    ): ProjectFacilityRenewal {
        return ProjectFacilityRenewalFactory::new()->create([
            'price_type' => $priceType,
            'work_cycle' => $workCycle,
            'financial_cycle' => $financialCycle,
            'price_value' => $priceValue,
            'work_cycle_value' => $workCycleValue,
            'financial_cycle_value' => $financialCycleValue ?? 0,
            'days' => $days ?? 0,
            'created_at' => $createdAt ?? Carbon::now(),
            'status' => 1,
        ]);
    }

    /**
     * @test
     *
     * @dataProvider zero_return_combinations_provider
     */
    public function test_returns_zero_for_specified_combinations($priceType, $workCycle, $financialCycle): void
    {
        $facility = $this->createFacilityRenewal($priceType, $workCycle, $financialCycle);
        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(0, $result);
    }

    public static function zero_return_combinations_provider(): array
    {
        return [
            'all none' => [PriceType::None, WorkCycle::None, FinancialCycle::None],
            'package none none' => [PriceType::Package, WorkCycle::None, FinancialCycle::None],
            'input none none' => [PriceType::Input, WorkCycle::None, FinancialCycle::None],
            'none yearly none' => [PriceType::None, WorkCycle::Yearly, FinancialCycle::None],
            'none inputdate none' => [PriceType::None, WorkCycle::InputDate, FinancialCycle::None],
            'none none twentypercent' => [PriceType::None, WorkCycle::None, FinancialCycle::TwentyPercentCreationPrice],
            'package yearly none' => [PriceType::Package, WorkCycle::Yearly, FinancialCycle::None],
            'package inputdate none' => [PriceType::Package, WorkCycle::InputDate, FinancialCycle::None],
            'input yearly none' => [PriceType::Input, WorkCycle::Yearly, FinancialCycle::None],
            'input inputdate none' => [PriceType::Input, WorkCycle::InputDate, FinancialCycle::None],
            'none yearly twentypercent' => [PriceType::None, WorkCycle::Yearly, FinancialCycle::TwentyPercentCreationPrice],
            'none inputdate twentypercent' => [PriceType::None, WorkCycle::InputDate, FinancialCycle::TwentyPercentCreationPrice],
        ];
    }

    public function test_calculates_package_price_correctly(): void
    {
        $packagePriceAtRenewal = 1000;
        $facility = $this->createFacilityRenewal(
            PriceType::Package,
            WorkCycle::None,
            FinancialCycle::CalcFromPackage,
            priceValue: 0,
            financialCycleValue: $packagePriceAtRenewal
        );

        // First calculation in invoice
        $result = FacilityCalculate::calc(
            $facility,
            $packagePriceAtRenewal,
            null,
            true
        );
        $this->assertEquals(200, $result); // 0.2 * 1000

        // Subsequent calculations in same invoice
        $result = FacilityCalculate::calc(
            $facility,
            $packagePriceAtRenewal,
            null,
            false
        );
        $this->assertEquals(0, $result);
    }

    public function test_calculates_yearly_price_correctly(): void
    {
        $facility = $this->createFacilityRenewal(
            PriceType::Input,
            WorkCycle::Yearly,
            FinancialCycle::InputPrice,
            1000,
            workCycleValue: 12
        );

        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(12000, $result);
    }

    public function test_calculates_date_based_price_correctly(): void
    {
        $createdAt = Carbon::now()->subDays(30);
        $renewalDate = Carbon::now();

        $facility = $this->createFacilityRenewal(
            PriceType::Input,
            WorkCycle::InputDate,
            FinancialCycle::InputPrice,
            3650, // 10 per day for easy calculation
            days: 30,
            createdAt: $createdAt
        );

        $result = FacilityCalculate::calc($facility, 0, $renewalDate);
        $this->assertEquals(300, $result); // (3650/365) * 30 days
    }

    public function test_returns_price_value_for_input_price(): void
    {
        $facility = $this->createFacilityRenewal(
            PriceType::Input,
            WorkCycle::None,
            FinancialCycle::InputPrice,
            1000
        );

        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(1000, $result);
    }

    public function test_returns_financial_cycle_value_for_calc_from_package(): void
    {
        $facility = $this->createFacilityRenewal(
            PriceType::Package,
            WorkCycle::None,
            FinancialCycle::CalcFromPackage,
            priceValue: 1000,
            financialCycleValue: 2000
        );

        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(400, $result); // 0.2 * 2000
    }

    public function test_uses_days_for_input_date_work_cycle(): void
    {
        $facility = $this->createFacilityRenewal(
            PriceType::Input,
            WorkCycle::InputDate,
            FinancialCycle::InputPrice,
            3650,
            days: 45
        );

        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(450, $result); // (3650/365) * 45
    }

    public function test_handles_zero_values_correctly(): void
    {
        $facility = $this->createFacilityRenewal(
            PriceType::Input,
            WorkCycle::Yearly,
            FinancialCycle::InputPrice,
            0,
            workCycleValue: 0,
            financialCycleValue: 0,
            days: 0
        );

        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(0, $result);
    }

    public function test_handles_null_values_safely(): void
    {
        $facility = $this->createFacilityRenewal(
            PriceType::Input,
            WorkCycle::InputDate,
            FinancialCycle::InputPrice,
            1000,
            null,
            null,
            null
        );

        $result = FacilityCalculate::calc($facility);
        $this->assertEquals(1000, $result);
    }
}
