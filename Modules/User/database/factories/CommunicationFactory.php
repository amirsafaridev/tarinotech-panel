<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\app\Models\Communication;

/**
 * @extends Factory
 */
class CommunicationFactory extends Factory
{
    protected $model = Communication::class;

    private array $social = [
        'انستاگرام',
        'فیسبوک',
        'توییتر',
        'لینکداین',
        'تلگرام',
        'واتس آپ',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement($this->social),
        ];
    }
}
