<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyResponse;
use Str;

class SurveyResponseFactory extends Factory
{
    protected $model = SurveyResponse::class;

    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-1 month');
        $completedAt = fake()->dateTimeBetween($startedAt, $startedAt->format('Y-m-d H:i:s').' +30 minutes');

        return [
            'survey_id' => Survey::factory(),
            'user_id' => null,
            'respondent_email' => fake()->email(),
            'respondent_name' => fake()->name().' '.fake()->lastName(),
            'ip_address' => fake()->ipv4(),
            'session_id' => Str::random(40),
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
        ];
    }
}
