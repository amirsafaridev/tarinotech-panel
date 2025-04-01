<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Models\User;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,

            'en_first_name' => $this->faker->randomElement(['Alice', 'Bob', 'Charlie', 'David', 'Ella', 'Frank', 'Grace', 'Hannah', 'Isaac', 'Jack', 'Katherine', 'Liam', 'Mia', 'Nathan', 'Olivia', 'Peter', 'Quinn', 'Rachel', 'Samuel', 'Taylor', 'Uma', 'Victor', 'Wendy', 'Xander', 'Yasmine', 'Zachary']),
            'en_last_name' => $this->faker->randomElement(['Smith', 'Johnson', 'Brown', 'Taylor', 'Anderson', 'Martinez', 'Wilson', 'Clark', 'Lee', 'Walker']),

            'father_name' => $this->faker->firstName.' '.$this->faker->lastName,
            'national_id' => $this->faker->numerify('########'),
            'document_id' => $this->faker->numerify('########'),

            'email' => $this->faker->unique()->email(),

            'dob' => $this->faker->date,
            'person_type' => PersonType::getRandomValue(),
            'official_bill' => $this->faker->boolean,
            'mobile' => $this->faker->unique()->numerify('98935#######'),
            'verify_at' => $this->faker->randomElement([null, $this->faker->date]),
            'is_block' => false,
            'user_type' => UserType::getRandomValue(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
