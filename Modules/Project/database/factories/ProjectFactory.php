<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\Project;

/**
 * @extends Factory
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyNames = [
            'پارس الکتریک',
            'ایران خودرو',
            'پتروشیمی فارسیان',
            'نفت ایران',
            'خدمات مالی ایران',
            'پالایش نفت اصفهان',
            'مهندسی برق ایران',
            'معماری و ساختمانی پارس',
            'صنعتی سایپا',
            'ایران کربن',
            'معدنی ایران زمین',
            'تولیدی پارس خودرو',
            'خدمات مسافرتی ایرانسیر',
            'مخابرات ایران',
            'نساجی ایران',
            'پخش مواد غذایی ایرانی',
            'پتروشیمی ایران',
            'الکترونیک ایران',
            'تولیدی پتروشیمی اصفهان',
            'توسعه صنعتی ایران',
        ];

        return [
            'title' => $this->faker->randomElement($companyNames),
            'domain' => $this->faker->domainName,
            'admin_id' => rand(1, 10),
            'user_id' => rand(1, 50),
            'base_id' => rand(1, 3),
            'type_id' => rand(1, 3),
            'status_id' => rand(1, 6),
            'price' => $this->faker->randomElement([10000, 50000, 600000, 80000, 10000000, 200000000]),
            'tax_rate' => config('factor.tax'),
            'note' => $this->faker->paragraph,
            'agreement_at' => now()->addMonths(rand(1, 5))->startOfMonth(),
            'created_at' => now()->addMonths(rand(1, 5))->startOfMonth(),
            'updated_at' => now()->addMonths(rand(1, 5))->startOfMonth(),
        ];
    }
}
