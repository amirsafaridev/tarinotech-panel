<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyQuestion;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyAnswerFactory extends Factory
{
    protected $model = SurveyAnswer::class;

    public function definition(): array
    {
        return [
            'response_id' => SurveyResponse::factory(),
            'question_id' => SurveyQuestion::factory(),
            'answer_text' => null,
            'rating_value' => null,
        ];
    }

    public function forTextQuestion()
    {
        return $this->state(function (array $attributes) {
            $persianTexts = [
                'پروژه شما بسیار عالی بود. از همکاری با شما خوشحالم.',
                'وب‌سایت طراحی شده کاملا مطابق نیازهای ما بود.',
                'سرعت انجام پروژه بسیار خوب بود.',
                'همکاری با تیم شما تجربه خوبی بود.',
                'کیفیت کار شما عالی است.',
                'طراحی سایت بسیار زیبا و کاربرپسند بود.',
                'پشتیبانی خوبی ارائه کردید.',
                'قیمت خدمات شما منصفانه است.',
                'نتیجه کار بهتر از انتظار ما بود.',
                'توضیحات شما درباره فرآیند کار بسیار روشن بود.',
                'تیم شما حرفه‌ای و متخصص هستند.',
                'می‌توانید در طراحی موبایل بهبودهایی ایجاد کنید.',
                'سرعت لود سایت می‌تواند بهتر شود.',
                'محتوای سایت نیاز به بهینه‌سازی بیشتری دارد.',
                'ما به دنبال همکاری طولانی مدت با شما هستیم.',
                'سئوی سایت به خوبی انجام شده است.',
                'رتبه سایت ما در گوگل بهبود یافته است.',
                'اپلیکیشن طراحی شده نیازهای ما را برآورده کرد.',
                'ممنون از راهنمایی‌های شما در طول پروژه.',
                'ما شرکت شما را به دیگران نیز معرفی خواهیم کرد.',
            ];

            return [
                'answer_text' => $this->faker->randomElement($persianTexts),
                'rating_value' => null,
            ];
        });
    }

    public function forRatingQuestion()
    {
        return $this->state(function (array $attributes) {
            return [
                'answer_text' => null,
                'rating_value' => $this->faker->numberBetween(1, 5),
            ];
        });
    }
}
