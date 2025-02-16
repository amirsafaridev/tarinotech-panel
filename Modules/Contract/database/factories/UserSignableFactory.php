<?php

namespace Modules\Contract\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Contract\app\Models\UserSignable;

/**
 * @extends Factory
 */
class UserSignableFactory extends Factory
{
    protected $model = UserSignable::class;

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
