<?php

namespace Modules\Package\database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Package\app\Models\Package;
use Modules\Package\app\Models\PackagePrice;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'title' => 'پیشرفته',
                'base_price' => 3000000,
                'type_id' => rand(1, 8),
            ],
            [
                'title' => 'اقتصادی',
                'base_price' => 1500000,
                'type_id' => rand(1, 8),
            ],
            [
                'title' => 'طلایی',
                'base_price' => 4500000,
                'type_id' => rand(1, 8),
            ],
        ];

        foreach ($packages as $packageData) {
            // Create package
            $package = Package::create([
                'title' => $packageData['title'],
                'type_id' => $packageData['type_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create prices for the last 3 years with 20% yearly increase
            for ($year = 3; $year >= 1; $year--) {
                $startDate = Carbon::now()->subYears($year)->startOfYear();
                $endDate = ($year > 1)
                    ? Carbon::now()->subYears($year - 1)->startOfYear()->subDay()
                    : null;

                // Calculate price with 20% increase each year
                $yearlyPrice = $packageData['base_price'] * pow(1.2, 3 - $year);

                PackagePrice::create([
                    'package_id' => $package->id,
                    'price' => round($yearlyPrice),
                    'start_at' => $startDate,
                    'end_at' => $endDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
