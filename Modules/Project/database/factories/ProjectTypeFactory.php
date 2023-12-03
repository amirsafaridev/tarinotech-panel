<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\ProjectType;

/**
 * @extends Factory
 */
class ProjectTypeFactory extends Factory
{
    protected $model = ProjectType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'نوع پروژه',
            'base_id' => rand(1, 3),
        ];
    }
}
