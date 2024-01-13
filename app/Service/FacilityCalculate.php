<?php

namespace App\Service;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use Modules\Project\app\Models\ProjectFacility;

class FacilityCalculate
{
    public static function calc(ProjectFacility $facility): int
    {
        if (
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::TwentyPercentCreationPrice
            )
            ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::None
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::None
            )
            ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::TwentyPercentCreationPrice
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::TwentyPercentCreationPrice
            )
        ) {
            return 0;
        }

        if (
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::None &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::None &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::Package &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::Yearly &&
                $facility->financial_cycle == FinancialCycle::InputLater
            ) ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::InputPrice
            )
            ||
            (
                $facility->price_type == PriceType::Input &&
                $facility->work_cycle == WorkCycle::InputDate &&
                $facility->financial_cycle == FinancialCycle::InputLater
            )
        ) {
            return $facility->price_value;
        }

        return 1;
    }
}
