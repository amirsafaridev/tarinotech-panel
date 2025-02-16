<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'طراحی UI/UX اختصاصی',
            'طراحی لوگو و برندینگ',
            'هاست ابری پیشرفته 10GB',
            'هاست اختصاصی SSD',
            'دامنه اختصاصی ir.',
            'دامنه بین‌المللی com.',
            'گواهی SSL رایگان',
            'نماد اعتماد الکترونیک',
            'پشتیبانی 24/7',
            'بهینه‌سازی سئو',
            'کنترل پنل پیشرفته',
            'سیستم مدیریت محتوا',
            'امنیت پیشرفته',
            'پشتیبان‌گیری خودکار',
            'ایمیل سازمانی',
        ];

        $data = array_map(function ($facility) {
            return [
                'title' => $facility,
                'base_id' => ProjectBase::Web,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $facilities);

        Facility::insert($data);
    }
}
