<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

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
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Package::query()->insert($dataToInsert);
    }
}
