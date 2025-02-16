<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\Facility;

/**
 * @extends Factory
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Facility::class;

    public function definition(): array
    {
        $additionalFeatures = [
            'طراحی گرافیکی ویژه',
            'هاست سی پنل 1 گیگ',
            'دامنه ir',
            'دامنه com',
            'هاست دانلودی 10 گیگ',
            'افزونه دیجیتز',
            'نماد اعتماد الکترونیک',
            'برنامه نویسی اختصاصی',
        ];

        return [
            'title' => $this->faker->randomElement($additionalFeatures),
            'base_id' => rand(1, 3),
        ];
    }
}
