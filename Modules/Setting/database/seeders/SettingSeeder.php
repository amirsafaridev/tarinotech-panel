<?php

namespace Modules\Setting\database\seeders;

use App\Enums\Database\Setting\SettingItems;
use Illuminate\Database\Seeder;
use Modules\Setting\app\Models\Setting;

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
