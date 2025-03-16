<?php

namespace Modules\Survey\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyResponseFactory extends Factory
{
    protected $model = SurveyResponse::class;

    public function definition(): array
    {
        $persianFirstNames = [
            'علی', 'محمد', 'حسین', 'رضا', 'محسن', 'امیر', 'مهدی', 'سعید', 'جواد', 'حسن',
            'فاطمه', 'زهرا', 'مریم', 'سارا', 'نرگس', 'لیلا', 'زینب', 'سمیرا', 'نازنین', 'پریسا',
        ];

        $persianLastNames = [
            'محمدی', 'حسینی', 'رضایی', 'موسوی', 'علوی', 'کریمی', 'نجفی', 'هاشمی', 'احمدی', 'اکبری',
            'مرادی', 'جعفری', 'قاسمی', 'صادقی', 'طاهری', 'عباسی', 'سلیمانی', 'یوسفی', 'رحیمی', 'امینی',
        ];

        $persianEmailProviders = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'mail.ir', 'iran.ir', 'chmail.ir'];

        $firstName = $this->faker->randomElement($persianFirstNames);
        $lastName = $this->faker->randomElement($persianLastNames);
        $emailProvider = $this->faker->randomElement($persianEmailProviders);

        // Create Persian-looking email
        $email = strtolower(
            $this->faker->randomElement(['', '.']).
            $this->transliterateToLatin($firstName).
            $this->faker->randomElement(['', '.', '_']).
            $this->transliterateToLatin($lastName).
            $this->faker->randomElement(['', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']).
            '@'.$emailProvider
        );

        return [
            'survey_id' => Survey::factory(),
            'user_id' => null,
            'respondent_email' => $email,
            'respondent_name' => $firstName.' '.$lastName,
            'ip_address' => $this->faker->ipv4(),
            'session_id' => \Str::random(40),
        ];
    }

    private function transliterateToLatin($text)
    {
        $translation = [
            'علی' => 'ali', 'محمد' => 'mohammad', 'حسین' => 'hossein', 'رضا' => 'reza', 'محسن' => 'mohsen',
            'امیر' => 'amir', 'مهدی' => 'mehdi', 'سعید' => 'saeed', 'جواد' => 'javad', 'حسن' => 'hasan',
            'فاطمه' => 'fateme', 'زهرا' => 'zahra', 'مریم' => 'maryam', 'سارا' => 'sara', 'نرگس' => 'narges',
            'لیلا' => 'leila', 'زینب' => 'zeinab', 'سمیرا' => 'samira', 'نازنین' => 'nazanin', 'پریسا' => 'parisa',
            'محمدی' => 'mohammadi', 'حسینی' => 'hosseini', 'رضایی' => 'rezaei', 'موسوی' => 'mousavi', 'علوی' => 'alavi',
            'کریمی' => 'karimi', 'نجفی' => 'najafi', 'هاشمی' => 'hashemi', 'احمدی' => 'ahmadi', 'اکبری' => 'akbari',
            'مرادی' => 'moradi', 'جعفری' => 'jafari', 'قاسمی' => 'ghasemi', 'صادقی' => 'sadeghi', 'طاهری' => 'taheri',
            'عباسی' => 'abbasi', 'سلیمانی' => 'soleimani', 'یوسفی' => 'yousefi', 'رحیمی' => 'rahimi', 'امینی' => 'amini',
        ];

        return $translation[$text] ?? strtolower(str_replace(' ', '', $text));
    }
}
