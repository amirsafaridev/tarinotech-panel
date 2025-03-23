<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyQuestion;

class SurveyQuestionFactory extends Factory
{
    protected $model = SurveyQuestion::class;

    public function definition(): array
    {
        return [
            'survey_id' => Survey::factory(),
            'question_text' => $this->faker->sentence().'?',
            'question_type' => $this->faker->randomElement(QuestionTypeEnum::getValues()),
            'order' => $this->faker->numberBetween(1, 20),
            'is_required' => $this->faker->boolean(80),
            'settings' => null,
        ];
    }

    public function singleChoice()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => QuestionTypeEnum::Single,
            ];
        });
    }

    public function multipleChoice()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => QuestionTypeEnum::Multiple,
            ];
        });
    }

    public function text()
    {
        return $this->state(function (array $attributes) {
            return [
                'question_type' => QuestionTypeEnum::Text,
            ];
        });
    }
}
