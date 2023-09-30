<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\Setting\SettingItems;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $title = 'تنظیمات پایه';
        $updateRoute = route('admin.setting.update');

        // Get all settings as a key-value array
        $settings = Setting::query()->pluck('value', 'key')->all();

        // Get the list of all possible setting keys
        $allSettingKeys = SettingItems::asArray();

        // Create settings that do not exist
        foreach ($allSettingKeys as $item) {
            if (! array_key_exists($item, $settings)) {
                $settings[$item] = '';
                Setting::query()->create([
                    'key' => $item,
                    'value' => '',
                ]);
            }
        }

        return view('admin.setting.edit', compact('title', 'updateRoute', 'settings'));
    }

    public function update(UpdateRequest $request)
    {
        $settings = Setting::query()
            ->whereIn('key', SettingItems::asArray())
            ->get()
            ->keyBy('key');

        foreach (SettingItems::asArray() as $item) {
            $data = $request->input($item, '');
            $value = $settings->get($item)->value ?? '';

            if ($data !== $value) {
                $this->settingSet($item, $data);
            }
        }

        return response()->json([
            'result' => 'success',
            'message' => trans('panel.success_update'),
        ]);
    }

    private function settingSet($key, $value)
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
