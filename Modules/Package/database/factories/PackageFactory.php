<?php

namespace Modules\Package\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Package\app\Models\Package;

/**
 * @extends Factory
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }
}
