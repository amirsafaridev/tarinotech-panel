<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Models\SurveyQuestion;
use Modules\Survey\app\Models\SurveyQuestionOption;

class SurveyQuestionOptionFactory extends Factory
{
    protected $model = SurveyQuestionOption::class;

    public function definition(): array
    {
        static $order = 1;

        return [
            'question_id' => SurveyQuestion::factory(),
            'option_text' => $this->faker->word(),
            'order' => $order++,
            'color_code' => $this->faker->hexColor(),
        ];
    }
}
