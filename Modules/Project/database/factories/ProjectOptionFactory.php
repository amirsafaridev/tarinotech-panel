<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\ProjectOption;

/**
 * @extends Factory
 */
class ProjectOptionFactory extends Factory
{
    protected $model = ProjectOption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'امکانات '.$this->faker->numerify('##'),
            'base_id' => rand(1, 3),
        ];
    }
}
