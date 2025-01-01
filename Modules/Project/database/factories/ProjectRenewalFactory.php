<?php

namespace Modules\Project\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\App\Models\ProjectRenewal;

/**
 * @extends Factory<ProjectRenewal>
 */
class ProjectRenewalFactory extends Factory
{
    protected $model = ProjectRenewal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
