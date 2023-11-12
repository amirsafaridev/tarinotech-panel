<?php

namespace Tests\Unit;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use App\Models\ProjectFacility;
use App\Service\FacilityCalculate;
use PHPUnit\Framework\TestCase;

class FacilityTest extends TestCase
{
    const DEFAULT_PRICE = 25000;

    /**
     * A basic test example.
     */
    private function makeFacility(int $price, int $work, int $financial): ProjectFacility
    {
        return new ProjectFacility([
            'price_type' => $price,
            'price_value' => self::DEFAULT_PRICE,
            'work_cycle' => $work,
            'financial_cycle' => $financial,
        ]);
    }

    public function test_f1(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::None, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f2(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::None, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f3(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::None, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f4(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::Yearly, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f5(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::InputDate, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f6(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::None, FinancialCycle::TwentyPercentCreationPrice)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f7(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::Yearly, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f8(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::InputDate, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f9(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::Yearly, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f10(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::InputDate, FinancialCycle::None)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f11(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::Yearly, FinancialCycle::TwentyPercentCreationPrice)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f12(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::InputDate, FinancialCycle::TwentyPercentCreationPrice)
        );
        $this->assertEquals(0, $result);
    }

    public function test_f13(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::None, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f14(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::None, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f15(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::None, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f16(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::None, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f17(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::None, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f18(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::None, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f19(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::Yearly, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f20(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::Yearly, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f21(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::InputDate, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f22(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::None, WorkCycle::InputDate, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f23(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::Yearly, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f24(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::Yearly, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f25(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::InputDate, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f26(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Package, WorkCycle::InputDate, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f27(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::Yearly, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f28(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::Yearly, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f29(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::InputDate, FinancialCycle::InputPrice)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }

    public function test_f30(): void
    {
        $result = FacilityCalculate::calc(
            $this->makeFacility(PriceType::Input, WorkCycle::InputDate, FinancialCycle::InputLater)
        );
        $this->assertEquals(self::DEFAULT_PRICE, $result);
    }
}
