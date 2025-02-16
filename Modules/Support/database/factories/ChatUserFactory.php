<?php

namespace Modules\Support\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Support\app\Models\ChatUser;

class ChatUserFactory extends Factory
{
    protected $model = ChatUser::class;

    public function definition(): array
    {
        return [
            'chat_id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'user_type' => $this->faker->word(),
            'seen_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
