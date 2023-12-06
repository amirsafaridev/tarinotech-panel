<?php

namespace Modules\Admin\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\app\Models\JobTitle;

/**
 * @extends Factory
 */
class JobTitleFactory extends Factory
{
    protected $model = JobTitle::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $jobTitles = [
            'مدیر پروژه',
            'برنامه نویس',
            'مهندس نرم‌افزار',
            'مدیر فروش',
            'متخصص بازاریابی',
            'مدیر منابع انسانی',
            'متخصص امور مالی',
            'مدیر عامل',
        ];

        return [
            'title' => $this->faker->randomElement($jobTitles),
        ];
    }
}
