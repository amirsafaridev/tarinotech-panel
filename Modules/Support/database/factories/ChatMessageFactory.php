<?php

namespace Modules\Support\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Support\app\Models\ChatMessage;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition(): array
    {
        return [
            'chat_id' => $this->faker->randomNumber(),
            'parent_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'user_type' => $this->faker->word(),
            'content' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
