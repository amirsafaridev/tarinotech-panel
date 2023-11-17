<?php

namespace Modules\Factor\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Factor\app\Models\Factor;

/**
 * @extends Factory
 */
class FactorFactory extends Factory
{
    protected $model = Factor::class;

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
