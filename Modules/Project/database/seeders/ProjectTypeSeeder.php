<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectType;

use function now;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectBaseTypes = [
            ProjectBase::Seo => [
                'پروژه سئو',
            ],
            ProjectBase::Ads => [
                'تبلیغات',
            ],
            ProjectBase::Web => [
                'شرکتی',
                'فروشگاهی',
                'خبری',
                'شخصی',
                'آموزشی',
                'درج آگهی',
            ],
        ];

        $projectTypesToInsert = [];

        foreach ($projectBaseTypes as $baseId => $typeTitles) {
            foreach ($typeTitles as $title) {
                $projectTypesToInsert[] = [
                    'title' => $title,
                    'base_id' => $baseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        ProjectType::query()->insert($projectTypesToInsert);
    }
}
