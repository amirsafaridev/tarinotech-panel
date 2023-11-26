<?php

namespace Database\Seeders;

use App\Enums\Database\Project\ProjectBase;
use App\Models\ProjectType;
use Illuminate\Database\Seeder;

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
