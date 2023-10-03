<?php

namespace Database\Seeders;

use App\Models\FreeDay;
use Illuminate\Database\Seeder;

class FreeDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $iranianHolidays = [
            [
                'title' => 'نوروز',
                'date' => '2022-03-21',
            ],
            [
                'title' => 'عید نوروز',
                'date' => '2022-03-20',
            ],
            [
                'title' => 'عید نوروز (ادامه)',
                'date' => '2022-03-21',
            ],
            [
                'title' => 'عید نوروز (ادامه)',
                'date' => '2022-03-22',
            ],
            [
                'title' => 'عید نوروز (ادامه)',
                'date' => '2022-03-23',
            ],
            [
                'title' => 'عید نوروز (ادامه)',
                'date' => '2022-03-24',
            ],
            [
                'title' => 'جشن سیزده به در',
                'date' => '2022-04-01',
            ],
            [
                'title' => 'عید رمضان',
                'date' => '2022-04-13',
            ],
            [
                'title' => 'عید رمضان (ادامه)',
                'date' => '2022-04-14',
            ],
            [
                'title' => 'عید رمضان (ادامه)',
                'date' => '2022-04-15',
            ],
            [
                'title' => 'شهادت امام علی (ع)',
                'date' => '2022-05-03',
            ],
            [
                'title' => 'عید قربان',
                'date' => '2022-07-20',
            ],
            [
                'title' => 'عید قربان (ادامه)',
                'date' => '2022-07-21',
            ],
            [
                'title' => 'عید قربان (ادامه)',
                'date' => '2022-07-22',
            ],
            [
                'title' => 'عید قربان (ادامه)',
                'date' => '2022-07-23',
            ],
        ];

        $dataToInsert = [];

        foreach ($iranianHolidays as $holiday) {
            $dataToInsert[] = [
                'title' => $holiday['title'],
                'free_at' => $holiday['date'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        FreeDay::query()->insert($dataToInsert);

    }
}
