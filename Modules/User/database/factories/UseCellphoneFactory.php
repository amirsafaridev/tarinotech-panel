<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\app\Models\UseCellphone;

class UseCellphoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = UseCellphone::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
