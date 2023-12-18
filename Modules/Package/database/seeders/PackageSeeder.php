<?php

namespace Modules\Package\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Package\app\Models\Package;

use function now;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = ['پیشرفته', 'اقتصادی', 'طلایی'];

        $dataToInsert = [];

        foreach ($packages as $package) {
            $dataToInsert[] = [
                'title' => $package,
                'base_id' => rand(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Package::query()->insert($dataToInsert);
    }
}
