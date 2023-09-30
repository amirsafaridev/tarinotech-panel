<?php

namespace Database\Seeders;

use App\Models\ProjectStatus;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['درحال طراحی', 'در انتظار پرداخت', 'انجام شده', 'پشتیبانی', 'طراحی گرافیکی', 'لغو شده'];

        $dataToInsert = [];

        foreach ($statuses as $status) {
            $dataToInsert[] = [
                'title' => $status,
                'project_type_id' => rand(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ProjectStatus::query()->insert($dataToInsert);
    }
}
