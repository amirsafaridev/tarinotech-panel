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
        $types = ['طراحی سایت', 'سئو', 'تبلیغات ادورز'];

        $dataToInsert = [];

        foreach ($types as $type) {
            $dataToInsert[] = [
                'title' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ProjectType::query()->insert($dataToInsert);
    }
}
