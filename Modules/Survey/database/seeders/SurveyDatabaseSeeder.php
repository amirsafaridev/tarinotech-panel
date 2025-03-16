<?php

namespace Modules\Survey\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Admin\app\Models\Admin;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyAnswerOption;
use Modules\Survey\app\Models\SurveyQuestion;
use Modules\Survey\app\Models\SurveyQuestionOption;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();
        if (! $admin) {
            $this->command->info('No admin found. Creating one...');
            $admin = Admin::query()->create([
                'name' => 'مدیر سایت',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $survey = Survey::query()->create([
            'title' => 'نظرسنجی رضایت مشتریان شرکت طراحی وب و سئو پارس دیجیتال',
            'description' => 'با تشکر از شما برای شرکت در این نظرسنجی. نظرات شما به ما کمک می‌کند تا خدمات خود را بهبود دهیم.',
            'admin_id' => $admin->id,
            'requires_auth' => false,
            'auth_guard' => null,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'access_token' => \Str::random(32),
        ]);

        $questions = [
            // Single choice questions
            [
                'question_text' => 'چگونه با شرکت ما آشنا شدید؟',
                'question_type' => QuestionTypeEnum::Single,
                'is_required' => true,
                'options' => [
                    'از طریق جستجو در اینترنت',
                    'معرفی توسط دوستان',
                    'تبلیغات در شبکه‌های اجتماعی',
                    'نمایشگاه‌ها و رویدادها',
                    'سایر موارد',
                ],
            ],
            [
                'question_text' => 'کدام خدمت ما را استفاده کرده‌اید؟',
                'question_type' => QuestionTypeEnum::Single,
                'is_required' => true,
                'options' => [
                    'طراحی وب‌سایت',
                    'سئو و بهینه‌سازی',
                    'طراحی اپلیکیشن موبایل',
                    'خدمات دیجیتال مارکتینگ',
                    'پشتیبانی و نگهداری سایت',
                ],
            ],
            [
                'question_text' => 'احتمال استفاده مجدد از خدمات ما چقدر است؟',
                'question_type' => QuestionTypeEnum::Single,
                'is_required' => true,
                'options' => [
                    'قطعاً استفاده خواهم کرد',
                    'احتمالاً استفاده خواهم کرد',
                    'مطمئن نیستم',
                    'احتمالاً استفاده نخواهم کرد',
                    'قطعاً استفاده نخواهم کرد',
                ],
            ],
            [
                'question_text' => 'آیا شرکت ما را به دوستان و همکاران خود معرفی می‌کنید؟',
                'question_type' => QuestionTypeEnum::Single,
                'is_required' => true,
                'options' => [
                    'بله، حتماً',
                    'احتمالاً بله',
                    'مطمئن نیستم',
                    'احتمالاً خیر',
                    'خیر',
                ],
            ],
            [
                'question_text' => 'از نظر شما، مهمترین ویژگی یک وب‌سایت خوب چیست؟',
                'question_type' => QuestionTypeEnum::Single,
                'is_required' => true,
                'options' => [
                    'طراحی زیبا و جذاب',
                    'سرعت لود مناسب',
                    'سازگاری با موبایل',
                    'محتوای مفید و کاربردی',
                    'امنیت بالا',
                ],
            ],

            // Multiple choice questions
            [
                'question_text' => 'کدام ویژگی‌های خدمات ما بیشتر مورد توجه شما قرار گرفته است؟ (چند گزینه)',
                'question_type' => QuestionTypeEnum::Single,
                'is_required' => true,
                'options' => [
                    'کیفیت بالای کار',
                    'قیمت مناسب',
                    'پشتیبانی خوب',
                    'انجام به موقع پروژه',
                    'خلاقیت در طراحی',
                    'تخصص فنی تیم',
                ],
            ],
            [
                'question_text' => 'به نظر شما، چه خدمات دیگری باید به مجموعه خدمات ما اضافه شود؟ (چند گزینه)',
                'question_type' => QuestionTypeEnum::Multiple,
                'is_required' => false,
                'options' => [
                    'طراحی هویت بصری و برندینگ',
                    'تولید محتوای تخصصی',
                    'طراحی UI/UX',
                    'توسعه اپلیکیشن‌های واقعیت افزوده',
                    'خدمات میزبانی وب',
                    'مشاوره استراتژی دیجیتال',
                ],
            ],
            [
                'question_text' => 'کدام یک از مشکلات زیر را در استفاده از خدمات ما تجربه کرده‌اید؟ (چند گزینه)',
                'question_type' => QuestionTypeEnum::Multiple,
                'is_required' => false,
                'options' => [
                    'تاخیر در تحویل پروژه',
                    'مشکلات فنی در محصول نهایی',
                    'ارتباط ضعیف تیم پشتیبانی',
                    'عدم تطابق نتیجه با انتظارات',
                    'قیمت بالا',
                    'هیچکدام',
                ],
            ],

            // Text questions
            [
                'question_text' => 'لطفاً نظرات و پیشنهادات خود را درباره خدمات طراحی وب‌سایت ما بنویسید:',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
            [
                'question_text' => 'چه بهبودهایی می‌توان در خدمات سئو و بهینه‌سازی ما ایجاد کرد؟',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
            [
                'question_text' => 'نظر شما درباره خدمات طراحی اپلیکیشن موبایل ما چیست؟',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
            [
                'question_text' => 'چه ویژگی‌هایی باعث شد شرکت ما را برای انجام پروژه انتخاب کنید؟',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
            [
                'question_text' => 'آیا پیشنهاد خاصی برای بهبود فرآیند همکاری با مشتریان دارید؟',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
            [
                'question_text' => 'به نظر شما، روند آتی صنعت طراحی وب و اپلیکیشن در ایران چگونه خواهد بود؟',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
            [
                'question_text' => 'آیا نکته دیگری هست که مایل باشید با ما در میان بگذارید؟',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
        ];

        // Create questions
        $order = 1;
        $questionModels = [];

        foreach ($questions as $questionData) {
            $question = SurveyQuestion::query()->create([
                'survey_id' => $survey->id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'order' => $order++,
                'is_required' => $questionData['is_required'],
                'settings' => $questionData['settings'] ?? null,
            ]);

            $questionModels[] = $question;

            // Create options for choice questions
            if (isset($questionData['options'])) {
                $optionOrder = 1;
                foreach ($questionData['options'] as $optionText) {
                    SurveyQuestionOption::query()->create([
                        'survey_question_id' => $question->id,
                        'option_text' => $optionText,
                        'order' => $optionOrder++,
                        'color_code' => '#'.substr(md5($optionText), 0, 6), // Generate a color based on the option text
                    ]);
                }
            }
        }

        $persianFirstNames = [
            'علی', 'محمد', 'حسین', 'رضا', 'محسن', 'امیر', 'مهدی', 'سعید', 'جواد', 'حسن',
            'فاطمه', 'زهرا', 'مریم', 'سارا', 'نرگس', 'لیلا', 'زینب', 'سمیرا', 'نازنین', 'پریسا',
        ];

        $persianLastNames = [
            'محمدی', 'حسینی', 'رضایی', 'موسوی', 'علوی', 'کریمی', 'نجفی', 'هاشمی', 'احمدی', 'اکبری',
            'مرادی', 'جعفری', 'قاسمی', 'صادقی', 'طاهری', 'عباسی', 'سلیمانی', 'یوسفی', 'رحیمی', 'امینی',
        ];

        $persianTexts = [
            'طراحی وب‌سایت شما عالی بود و کاملاً با نیازهای کسب و کار ما مطابقت داشت.',
            'خدمات سئو شما باعث افزایش چشمگیر بازدید سایت ما شده است.',
            'از پشتیبانی سریع و دقیق تیم شما بسیار راضی هستم.',
            'اپلیکیشن طراحی شده توسط تیم شما بسیار کاربرپسند و زیباست.',
            'فرآیند طراحی وب‌سایت به خوبی مدیریت شد و مطابق زمان‌بندی پیش رفت.',
            'خدمات سئوی شما نیاز به بهبود بیشتری دارد. نتایج هنوز مطلوب نیست.',
            'طراحی رابط کاربری اپلیکیشن بسیار حرفه‌ای بود و تجربه کاربری خوبی ایجاد کرد.',
            'ارتباط و پاسخگویی تیم شما عالی بود و همیشه در دسترس بودید.',
            'پیشنهاد می‌کنم خدمات تولید محتوا را هم به مجموعه خدمات خود اضافه کنید.',
            'مشاوره‌های فنی تیم شما بسیار کمک‌کننده بود و باعث شد تصمیم بهتری بگیریم.',
            'تیم شما در حل مشکلات فنی بسیار سریع و کارآمد عمل کرد.',
            'قیمت‌های شما نسبت به کیفیت خدمات‌تان منصفانه است.',
            'پیشنهاد می‌کنم روی سرعت لود وب‌سایت‌ها بیشتر کار کنید.',
            'بهتر است در زمینه طراحی ریسپانسیو برای دستگاه‌های مختلف دقت بیشتری داشته باشید.',
            'امیدوارم در آینده بتوانیم همکاری بیشتری با تیم حرفه‌ای شما داشته باشیم.',
            'به نظرم صنعت طراحی وب و اپلیکیشن در ایران رو به رشد است و شرکت‌های داخلی توانایی رقابت با نمونه‌های خارجی را دارند.',
            'اگر امکان پرداخت اقساطی یا تخفیف برای پروژه‌های بزرگ داشته باشید، بهتر است.',
            'به نظرم باید روی آموزش مشتریان برای استفاده بهتر از سیستم‌های طراحی شده وقت بیشتری بگذارید.',
            'بهتر است بعد از تحویل پروژه، پیگیری‌های بیشتری برای اطمینان از رضایت مشتری انجام دهید.',
            'گزارش‌های دوره‌ای از عملکرد سایت و بهینه‌سازی می‌تواند برای مشتریان مفید باشد.',
        ];

        $persianEmailProviders = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'mail.ir', 'iran.ir', 'chmail.ir'];

        // Create 100 responses
        for ($i = 0; $i < 100; $i++) {
            // Create a response
            $firstName = $persianFirstNames[array_rand($persianFirstNames)];
            $lastName = $persianLastNames[array_rand($persianLastNames)];

            // Create Persian-looking email
            $email = fake()->email();

            $response = SurveyResponse::query()->create([
                'survey_id' => $survey->id,
                'user_id' => null,
                'respondent_email' => $email,
                'respondent_name' => $firstName.' '.$lastName,
                'ip_address' => fake()->ipv4(),
                'session_id' => Str::random(40),
            ]);

            // Create answers for each question
            foreach ($questionModels as $question) {
                switch ($question->question_type) {
                    case 'rating':
                        // Create rating answer (1-5)
                        SurveyAnswer::query()->create([
                            'response_id' => $response->id,
                            'question_id' => $question->id,
                            'rating_value' => rand(1, 5),
                        ]);
                        break;

                    case 'text':
                        // Create text answer (50% chance of answering)
                        if (rand(0, 1) || $question->is_required) {
                            SurveyAnswer::query()->create([
                                'response_id' => $response->id,
                                'question_id' => $question->id,
                                'answer_text' => $persianTexts[array_rand($persianTexts)],
                            ]);
                        }
                        break;

                    case 'single_choice':
                        // Create single choice answer
                        $answer = SurveyAnswer::query()->create([
                            'response_id' => $response->id,
                            'question_id' => $question->id,
                        ]);

                        // Get a random option for this question
                        $option = $question->options()->inRandomOrder()->first();

                        // Create the answer option
                        if ($option) {
                            SurveyAnswerOption::query()->create([
                                'answer_id' => $answer->id,
                                'question_option_id' => $option->id,
                            ]);
                        }
                        break;

                    case 'multiple_choice':
                        // Create multiple choice answer
                        $answer = SurveyAnswer::query()->create([
                            'response_id' => $response->id,
                            'question_id' => $question->id,
                        ]);

                        // Get all options for this question
                        $options = $question->options()->get();

                        // Select 1-3 random options
                        $selectedOptions = $options->random(rand(1, min(3, $options->count())));

                        // Create the answer options
                        foreach ($selectedOptions as $option) {
                            SurveyAnswerOption::query()->create([
                                'answer_id' => $answer->id,
                                'question_option_id' => $option->id,
                            ]);
                        }
                        break;
                }
            }
        }
    }
}
