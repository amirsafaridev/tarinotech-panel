<?php

namespace Database\Factories;

use App\Enums\Database\Project\ProjectDesignBy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProjectAdsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_activity' => 'زمینه کاری',
            'designed_by' => ProjectDesignBy::getRandomValue(),
        ];
    }
}
