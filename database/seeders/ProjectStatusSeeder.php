<?php

namespace Database\Seeders;

use App\Enums\Database\Project\ProjectBase;
use App\Models\ProjectStatus;
use App\Models\ProjectType;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $statuses = [
            ProjectBase::Web => [
                'درحال طراحی',
                'در انتظار تأیید',
                'در حال توسعه',
                'انجام شده',
                'نیاز به به‌روزرسانی',
                'در انتظار بازخورد',
                'پیش‌نویس',
                'لغو شده',
            ],
            ProjectBase::Seo => [
                'تحلیل واژه‌ها',
                'بهینه‌سازی محتوا',
                'لینک‌سازی',
                'بررسی رقبا',
                'تحلیل عملکرد',
                'بهبودات',
                'انجام شده',
                'لغو شده',
            ],
            ProjectBase::Ads => [
                'تعیین استراتژی',
                'ایجاد کمپین‌ها',
                'تنظیم اهداف',
                'تأیید تبلیغات',
                'اپتیمایز کمپین‌ها',
                'بررسی عملکرد',
                'موفقیت‌آمیز',
                'لغو شده',
            ],
        ];

        $projectTypes = ProjectType::query()
            ->get();

        $statusesToInsert = [];

        foreach ($projectTypes as $projectType) {
            foreach ($statuses[$projectType->base_id] as $status) {
                $statusesToInsert[] = [
                    'title' => $status,
                    'type_id' => $projectType->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        ProjectStatus::query()->insert($statusesToInsert);
    }
}
