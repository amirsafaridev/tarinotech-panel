<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class AdditionalFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
            'project_type_id' => rand(1, 3),
        ];
    }
}
