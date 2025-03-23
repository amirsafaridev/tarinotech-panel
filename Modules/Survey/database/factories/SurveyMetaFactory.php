<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Models\SurveyMeta;

class SurveyMetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = SurveyMeta::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
