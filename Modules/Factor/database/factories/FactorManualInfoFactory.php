<?php

namespace Modules\Factor\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Factor\app\Models\FactorManualInfo;

class FactorManualInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = FactorManualInfo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
