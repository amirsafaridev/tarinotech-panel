<?php

namespace Modules\Project\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Models\BusinessDomain;

class BusinessDomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = BusinessDomain::class;

    /**
     * Define the model's default state.
     */
    private array $businessDomains = [
        'تکنولوژی',
        'مالی',
        'بهداشت',
        'آموزش',
        'خدمات رستورانی',
        'سفر و گردشگری',
        'موسیقی',
        'ورزش',
        'مد و لباس',
        'صنعتی و تولیدی',
        'خودرو و حمل‌ونقل',
    ];

    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement($this->businessDomains),
        ];
    }
}
