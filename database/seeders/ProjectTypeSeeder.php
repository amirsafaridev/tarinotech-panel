<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'شرکتی',
            'فروشگاهی',
            'خبری',
            'شخصی',
            'آموزشی',
            'درج آگهی',
            'سایر',
        ];

        $dataToInsert = [];

        foreach ($types as $type) {
            $dataToInsert[] = [
                'title' => $type,
                'project_base_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ProjectType::query()->insert($dataToInsert);
    }
}
