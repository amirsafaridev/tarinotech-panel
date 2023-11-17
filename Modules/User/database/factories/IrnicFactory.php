<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Modules\User\app\Enums\IrnicStatus;
use Modules\User\app\Models\Irnic;

/**
 * @extends Factory
 */
class IrnicFactory extends Factory
{
    protected $model = Irnic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => IrnicStatus::getRandomValue(),
            'identify' => $this->faker->numerify('IR####'),
            'password' => Crypt::encrypt('12345678'),
            'user_id' => rand(1, 50),
        ];
    }
}
