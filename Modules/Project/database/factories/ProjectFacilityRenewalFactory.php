<?php

namespace Modules\Project\Database\Factories;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\ProjectFacilityRenewal;

/**
 * @extends Factory<ProjectFacilityRenewal>
 */
class ProjectFacilityRenewalFactory extends Factory
{
    protected $model = ProjectFacilityRenewal::class;

    public function definition()
    {
        return [
            'facility_id' => 1,
            'project_renewal_id' => 1,
            'price_type' => $this->faker->randomElement([
                PriceType::None,
                PriceType::Package,
                PriceType::Input,
            ]),
            'price_value' => $this->faker->numberBetween(100, 10000),
            'work_cycle' => $this->faker->randomElement([
                WorkCycle::None,
                WorkCycle::Yearly,
                WorkCycle::InputDate,
            ]),
            'work_cycle_value' => $this->faker->numberBetween(1, 365),
            'financial_cycle' => $this->faker->randomElement([
                FinancialCycle::None,
                FinancialCycle::InputPrice,
                FinancialCycle::CalcFromPackage,
                FinancialCycle::InputLater,
                FinancialCycle::TwentyPercentCreationPrice,
            ]),
            'financial_cycle_value' => $this->faker->numberBetween(100, 10000),
            'days' => $this->faker->numberBetween(1, 365),
            'status' => $this->faker->numberBetween(0, 1),
            'description' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
