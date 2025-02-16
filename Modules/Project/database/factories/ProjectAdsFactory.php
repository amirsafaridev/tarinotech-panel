<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Enums\ProjectDesignBy;
use Modules\Project\app\Models\ProjectAds;

/**
 * @extends Factory
 */
class ProjectAdsFactory extends Factory
{
    protected $model = ProjectAds::class;

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
