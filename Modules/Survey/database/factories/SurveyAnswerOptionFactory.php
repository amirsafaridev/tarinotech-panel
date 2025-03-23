<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyAnswerOption;
use Modules\Survey\app\Models\SurveyQuestionOption;

class SurveyAnswerOptionFactory extends Factory
{
    protected $model = SurveyAnswerOption::class;

    public function definition(): array
    {
        return [
            'answer_id' => SurveyAnswer::factory(),
            'question_option_id' => SurveyQuestionOption::factory(),
        ];
    }
}
