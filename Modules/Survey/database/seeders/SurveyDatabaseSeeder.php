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
        $admin = $this->getOrCreateAdmin();
        $survey = $this->createSurvey($admin);
        $questionModels = $this->createQuestions($survey);
        $this->createResponses($survey, $questionModels);
    }

    private function getOrCreateAdmin(): Admin
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

        return $admin;
    }

    private function createSurvey(Admin $admin): Survey
    {
        return Survey::query()->create([
            'title' => 'نظرسنجی رضایت مشتریان شرکت طراحی وب و سئو پارس دیجیتال',
            'description' => 'با تشکر از شما برای شرکت در این نظرسنجی. نظرات شما به ما کمک می‌کند تا خدمات خود را بهبود دهیم.',
            'admin_id' => $admin->id,
            'requires_auth' => false,
            'auth_guard' => null,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'access_token' => Str::random(32),
        ]);
    }

    private function createQuestions(Survey $survey): array
    {
        $questions = $this->getQuestionsData();
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

            if (isset($questionData['options'])) {
                $this->createQuestionOptions($question, $questionData['options']);
            }
        }

        return $questionModels;
    }

    private function createQuestionOptions(SurveyQuestion $question, array $options): void
    {
        $optionOrder = 1;
        foreach ($options as $optionText) {
            SurveyQuestionOption::query()->create([
                'survey_question_id' => $question->id,
                'option_text' => $optionText,
                'order' => $optionOrder++,
                'color_code' => '#'.substr(md5($optionText), 0, 6),
            ]);
        }
    }

    private function createResponses(Survey $survey, array $questionModels): void
    {
        $persianNames = $this->getPersianNames();
        $persianTexts = $this->getPersianTexts();

        for ($i = 0; $i < 100; $i++) {
            $firstName = $persianNames['firstNames'][array_rand($persianNames['firstNames'])];
            $lastName = $persianNames['lastNames'][array_rand($persianNames['lastNames'])];

            // Generate random start and completion times
            $startedAt = now()->subDays(rand(1, 30))->subHours(rand(1, 24));
            $completedAt = clone $startedAt;
            $completedAt->addMinutes(rand(3, 25))->addSeconds(rand(10, 59));

            $response = SurveyResponse::query()->create([
                'survey_id' => $survey->id,
                'respondent_email' => fake()->email(),
                'respondent_name' => $firstName.' '.$lastName,
                'ip_address' => fake()->ipv4(),
                'session_id' => Str::random(40),
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
            ]);

            $this->createAnswersForResponse($response, $questionModels, $persianTexts);
        }
    }

    private function createAnswersForResponse(SurveyResponse $response, array $questionModels, array $persianTexts): void
    {
        foreach ($questionModels as $question) {
            switch ($question->question_type) {
                case QuestionTypeEnum::Text:
                    if (rand(0, 1) || $question->is_required) {
                        SurveyAnswer::query()->create([
                            'response_id' => $response->id,
                            'survey_question_id' => $question->id,
                            'answer_text' => $persianTexts[array_rand($persianTexts)],
                        ]);
                    }
                    break;

                case QuestionTypeEnum::Single:
                    $this->createSingleChoiceAnswer($response, $question);
                    break;

                case QuestionTypeEnum::Multiple:
                    $this->createMultipleChoiceAnswer($response, $question);
                    break;
            }
        }
    }

    private function createSingleChoiceAnswer(SurveyResponse $response, SurveyQuestion $question): void
    {
        $answer = SurveyAnswer::query()->create([
            'response_id' => $response->id,
            'survey_question_id' => $question->id,
        ]);

        $option = $question->options()->inRandomOrder()->first();

        if ($option) {
            SurveyAnswerOption::query()->create([
                'answer_id' => $answer->id,
                'survey_question_option_id' => $option->id,
            ]);
        }
    }

    private function createMultipleChoiceAnswer(SurveyResponse $response, SurveyQuestion $question): void
    {
        $answer = SurveyAnswer::query()->create([
            'response_id' => $response->id,
            'survey_question_id' => $question->id,
        ]);

        $options = $question->options()->get();
        $selectedOptions = $options->random(rand(1, min(3, $options->count())));

        foreach ($selectedOptions as $option) {
            SurveyAnswerOption::query()->create([
                'answer_id' => $answer->id,
                'survey_question_option_id' => $option->id,
            ]);
        }
    }

    private function getPersianNames(): array
    {
        return [
            'firstNames' => [
                'علی', 'محمد', 'حسین', 'رضا', 'محسن', 'امیر', 'مهدی', 'سعید', 'جواد', 'حسن',
                'فاطمه', 'زهرا', 'مریم', 'سارا', 'نرگس', 'لیلا', 'زینب', 'سمیرا', 'نازنین', 'پریسا',
            ],
            'lastNames' => [
                'محمدی', 'حسینی', 'رضایی', 'موسوی', 'علوی', 'کریمی', 'نجفی', 'هاشمی', 'احمدی', 'اکبری',
                'مرادی', 'جعفری', 'قاسمی', 'صادقی', 'طاهری', 'عباسی', 'سلیمانی', 'یوسفی', 'رحیمی', 'امینی',
            ],
        ];
    }

    private function getPersianTexts(): array
    {
        return [
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
        ];
    }

    private function getQuestionsData(): array
    {
        // For brevity, I'm returning just a few questions instead of all of them
        return [
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
                'question_text' => 'لطفاً نظرات و پیشنهادات خود را درباره خدمات طراحی وب‌سایت ما بنویسید:',
                'question_type' => QuestionTypeEnum::Text,
                'is_required' => false,
            ],
        ];
    }
}
