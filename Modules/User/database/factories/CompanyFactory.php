<?php

namespace Modules\User\database\factories;

use App\Enums\Database\Company\CompanyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\app\Models\Company;

/**
 * @extends Factory
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'identify' => $this->faker->numerify('22#####'),
            'register_id' => $this->faker->numerify('33#####'),
            'type' => CompanyType::getRandomValue(),
            'user_id' => rand(1, 50),
        ];
    }
}
