<?php

namespace Modules\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\app\Models\KnowledgeWay;

class KnowledgeWayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = KnowledgeWay::class;

    private array $socialMediaWays = [
        'انستاگرام',
        'فیسبوک',
        'توییتر',
        'لینکداین',
        'تلگرام',
        'وبلاگ',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement($this->socialMediaWays),
        ];
    }
}
