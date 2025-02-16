<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\app\Models\Address;

/**
 * @extends Factory
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => $this->faker->address,
            'postal_code' => $this->faker->numerify('33616####'),
            'user_id' => rand(1, 50),
        ];
    }
}
