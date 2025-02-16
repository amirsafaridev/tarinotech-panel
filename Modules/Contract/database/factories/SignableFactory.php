<?php

namespace Modules\Contract\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Contract\app\Models\Signable;

class SignableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Signable::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
