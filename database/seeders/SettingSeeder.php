<?php

namespace Database\Seeders;

use App\Enums\Database\Setting\SettingItems;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SettingItems::asArray() as $item) {
            Setting::query()->create([
                'key' => $item,
                'value' => '',
            ]);
        }
    }
}
