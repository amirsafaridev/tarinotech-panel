<?php

namespace App\Service;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use Carbon\Carbon;
use Modules\Project\app\Models\ProjectFacilityRenewal;

class FacilityCalculate
{
    public static function calc(
        ProjectFacilityRenewal $facility,
        int|float $packagePriceAtRenewal = 0,
        ?Carbon $renewalDate = null,
        bool $isFirstCalcFromPackageInInvoice = true
    ): int {
        if (self::shouldReturnZero($facility)) {
            return 0;
        }

        $priceValue = is_numeric($facility->financial_cycle_value) ? (int) $facility->financial_cycle_value : 0;

        if ($facility->financial_cycle === FinancialCycle::CalcFromPackage) {
            if (! $isFirstCalcFromPackageInInvoice) {
                return 0;
            }

            $calcValue = $facility->financial_cycle_value > 0
                ? $facility->financial_cycle_value
                : $packagePriceAtRenewal;

            return (int) (0.2 * $calcValue);
        }

        if ($facility->work_cycle === WorkCycle::Yearly) {
            $multiplier = is_numeric($facility->work_cycle_value) && $facility->work_cycle_value > 0
                ? (int) $facility->work_cycle_value
                : 12;

            return $priceValue * $multiplier;
        }

        if ($facility->work_cycle === WorkCycle::InputDate) {
            if (is_numeric($facility->days) && $facility->days > 0) {
                return (int) ($priceValue * ($facility->days / 365));
            }

            return $priceValue;
        }

        if (self::shouldReturnPriceValue($facility)) {
            return $priceValue;
        }

        return 1;
    }

    private static function shouldReturnZero(ProjectFacilityRenewal $facility): bool
    {
        $zeroConditions = [
            [PriceType::None, WorkCycle::None, FinancialCycle::None],
            [PriceType::Package, WorkCycle::None, FinancialCycle::None],
            [PriceType::Input, WorkCycle::None, FinancialCycle::None],
            [PriceType::None, WorkCycle::Yearly, FinancialCycle::None],
            [PriceType::None, WorkCycle::InputDate, FinancialCycle::None],
            [PriceType::None, WorkCycle::None, FinancialCycle::TwentyPercentCreationPrice],
            [PriceType::Package, WorkCycle::Yearly, FinancialCycle::None],
            [PriceType::Package, WorkCycle::InputDate, FinancialCycle::None],
            [PriceType::Input, WorkCycle::Yearly, FinancialCycle::None],
            [PriceType::Input, WorkCycle::InputDate, FinancialCycle::None],
            [PriceType::None, WorkCycle::Yearly, FinancialCycle::TwentyPercentCreationPrice],
            [PriceType::None, WorkCycle::InputDate, FinancialCycle::TwentyPercentCreationPrice],
        ];

        foreach ($zeroConditions as [$priceType, $workCycle, $financialCycle]) {
            if ($facility->price_type === $priceType &&
                $facility->work_cycle === $workCycle &&
                $facility->financial_cycle === $financialCycle) {
                return true;
            }
        }

        return false;
    }

    private static function shouldReturnPriceValue(ProjectFacilityRenewal $facility): bool
    {
        $priceValueConditions = [
            [PriceType::None, WorkCycle::None, FinancialCycle::InputPrice],
            [PriceType::Package, WorkCycle::None, FinancialCycle::InputPrice],
            [PriceType::Input, WorkCycle::None, FinancialCycle::InputPrice],
            [PriceType::None, WorkCycle::Yearly, FinancialCycle::InputPrice],
            [PriceType::None, WorkCycle::InputDate, FinancialCycle::InputPrice],
            [PriceType::Package, WorkCycle::Yearly, FinancialCycle::InputPrice],
            [PriceType::Package, WorkCycle::InputDate, FinancialCycle::InputPrice],
            [PriceType::Input, WorkCycle::Yearly, FinancialCycle::InputPrice],
            [PriceType::Input, WorkCycle::InputDate, FinancialCycle::InputPrice],
            [PriceType::None, WorkCycle::None, FinancialCycle::InputLater],
            [PriceType::Package, WorkCycle::None, FinancialCycle::InputLater],
            [PriceType::Input, WorkCycle::None, FinancialCycle::InputLater],
            [PriceType::None, WorkCycle::Yearly, FinancialCycle::InputLater],
            [PriceType::None, WorkCycle::InputDate, FinancialCycle::InputLater],
            [PriceType::Package, WorkCycle::Yearly, FinancialCycle::InputLater],
            [PriceType::Package, WorkCycle::InputDate, FinancialCycle::InputLater],
            [PriceType::Input, WorkCycle::Yearly, FinancialCycle::InputLater],
            [PriceType::Input, WorkCycle::InputDate, FinancialCycle::InputLater],
        ];

        foreach ($priceValueConditions as [$priceType, $workCycle, $financialCycle]) {
            if ($facility->price_type === $priceType &&
                $facility->work_cycle === $workCycle &&
                $facility->financial_cycle === $financialCycle) {
                return true;
            }
        }

        return false;
    }
}
