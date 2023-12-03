<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\ProjectBase;

/**
 * @extends Factory
 */
class ProjectBaseFactory extends Factory
{
    protected $model = ProjectBase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'test',
        ];
    }
}
