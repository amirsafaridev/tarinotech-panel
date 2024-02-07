<?php

namespace Modules\Blog\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\app\Models\BlogCategory;

/**
 * @extends Factory
 */
class BlogCategoryFactory extends Factory
{
    protected $model = BlogCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'تکنولوژی',
            'سفر',
            'سلامت و تندرستی',
            'غذا و پخت و پز',
            'مد',
            'خانه و دکوراسیون',
            'مالیات',
            'آموزش',
            'سرگرمی',
            'سبک زندگی',
        ];

        return [
            'title' => $this->faker->randomElement($titles),
        ];
    }
}
