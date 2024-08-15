<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Project\app\Models\ProjectBase;

class ProjectBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bases = ['طراحی سایت', 'سئو', 'تبلیغات ادورز'];

        $dataToInsert = [];

        foreach ($bases as $base) {
            $dataToInsert[] = [
                'title' => $base,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ProjectBase::query()->insert($dataToInsert);
    }
}
