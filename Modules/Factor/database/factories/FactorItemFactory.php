<?php

namespace Modules\Factor\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Factor\app\Models\FactorItem;

/**
 * @extends Factory
 */
class FactorItemFactory extends Factory
{
    protected $model = FactorItem::class;

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
