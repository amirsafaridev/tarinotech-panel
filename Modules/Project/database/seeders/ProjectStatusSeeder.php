<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectStatus;
use Modules\Project\app\Models\ProjectType;

use function now;

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
