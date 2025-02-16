<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\app\Models\UserCommunication;

/**
 * @extends Factory
 */
class UserCommunicationFactory extends Factory
{
    protected $model = UserCommunication::class;

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
