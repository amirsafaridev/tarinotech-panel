<?php

namespace Modules\Support\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Support\app\Models\ChatBot;

class ChatBotFactory extends Factory
{
    protected $model = ChatBot::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'logo' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
