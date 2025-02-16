<?php

namespace Modules\Package\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Package\App\Models\PackageContractHistory;

/**
 * @extends Factory<PackageContractHistory>
 */
class PackageContractHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PackageContractHistory::class;

    public function definition(): array
    {
        return [
            //
        ];
    }
}
