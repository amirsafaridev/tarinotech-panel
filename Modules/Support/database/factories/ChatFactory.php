<?php

namespace Modules\Support\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Support\app\Models\Chat;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'logo' => $this->faker->word(),
            'type' => $this->faker->randomNumber(),
            'status' => $this->faker->randomNumber(),
            'project_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
