<?php

namespace Modules\Factor\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Factor\app\Models\TransactionCategory;

/**
 * @extends Factory
 */
class TransactionCategoryFactory extends Factory
{
    protected $model = TransactionCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeDeposits = [
            'پرداخت اولیه',
            'پرداخت مراحلی',
            'نرخ ساعتی',
            'قیمت ثابت',
            'تعهد ماهیانه',
            'با توجه به عملکرد',
            'درصد از فروش',
            'با توجه به حقوق',
            'سهمیه در سرمایه',
            'تقسیم سودها',
            'پرداخت بر اساس کلیک (PPC)',
            'پرداخت بر اساس کسب و کار (PPA)',
        ];

        return [
            'title' => $this->faker->randomElement($typeDeposits),
        ];
    }
}
