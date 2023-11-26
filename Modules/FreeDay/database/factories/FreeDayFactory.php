<?php

namespace Modules\FreeDay\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FreeDay\app\Models\FreeDay;

/**
 * @extends Factory
 */
class FreeDayFactory extends Factory
{
    protected $model = FreeDay::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' => 'Day',
            'free_at' => $this->faker->date,
        ];
    }
}
