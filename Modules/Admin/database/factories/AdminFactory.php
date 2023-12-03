<?php

namespace Modules\Admin\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\app\Models\Admin;

/**
 * @extends Factory
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'job_title' => $this->faker->randomElement($jobTitles),
            'mobile' => $this->faker->numerify('09#########'),
            'mobile_company' => $this->faker->numerify('09#########'),
            'number_company' => $this->faker->numerify('021######'),
            'email' => $this->faker->unique()->email,
            'password' => bcrypt('12345678'),
        ];
    }
}
