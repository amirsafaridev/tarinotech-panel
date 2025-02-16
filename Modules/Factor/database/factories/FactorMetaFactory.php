<?php

namespace Modules\Factor\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Factor\app\Models\FactorMeta;

/**
 * @extends Factory
 */
class FactorMetaFactory extends Factory
{
    protected $model = FactorMeta::class;

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
