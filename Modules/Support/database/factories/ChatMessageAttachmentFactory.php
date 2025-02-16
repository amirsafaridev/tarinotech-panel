<?php

namespace Modules\Support\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Support\app\Models\ChatMessageAttachment;

class ChatMessageAttachmentFactory extends Factory
{
    protected $model = ChatMessageAttachment::class;

    public function definition(): array
    {
        return [
            'chat_message_id' => $this->faker->randomNumber(),
            'type' => $this->faker->randomNumber(),
            'file_type' => $this->faker->word(),
            'file_extension' => $this->faker->word(),
            'file_size' => $this->faker->randomNumber(),
            'file_path' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
