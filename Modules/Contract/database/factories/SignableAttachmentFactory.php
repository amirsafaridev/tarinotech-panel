<?php

namespace Modules\Contract\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Contract\app\Models\SignableAttachment;

/**
 * @extends Factory
 */
class SignableAttachmentFactory extends Factory
{
    protected $model = SignableAttachment::class;

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
