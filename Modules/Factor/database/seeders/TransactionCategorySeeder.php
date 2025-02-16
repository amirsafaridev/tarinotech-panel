<?php

namespace Modules\Factor\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Factor\app\Models\TransactionCategory;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'پیش پرداخت طراحی سایت',
            'مرحله دوم طراحی سایت',
            'تسویه طراحی سایت',
            'شارژ ادورز گوگل',
            'پرداخت ماهانه سئو',
            'بیعانه طراحی لوگو',
            'تسویه طراحی لوگو',
            'تسویه طراحی لوگو',
            'خدمات پشتیبانی',
            'پکیج محتوای طراحی سایت',
            'پکیج ادمین سایت',
        ];
        $bulkInsert = [];
        foreach ($categories as $category) {
            $bulkInsert[] = [
                'title' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        TransactionCategory::query()
            ->insert($bulkInsert);
    }
}
