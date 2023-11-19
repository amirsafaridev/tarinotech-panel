<?php

namespace Modules\Login\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Login\app\Models\OtpCode;

/**
 * @extends Factory
 */
class OtpCodeFactory extends Factory
{
    protected $model = OtpCode::class;

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
