<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Admin\app\Models\Admin;
use Modules\Survey\app\Models\Survey;

class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    public function definition(): array
    {
        $adminId = Admin::first()->id ?? 1;

        return [
            'title' => 'نظرسنجی رضایت مشتریان شرکت طراحی وب و سئو پارس دیجیتال',
            'description' => 'با تشکر از شما برای شرکت در این نظرسنجی. نظرات شما به ما کمک می‌کند تا خدمات خود را بهبود دهیم.',
            'admin_id' => $adminId,
            'requires_auth' => false,
            'auth_guard' => null,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'access_token' => Str::random(32),
        ];
    }
}
