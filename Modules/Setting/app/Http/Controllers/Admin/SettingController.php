<?php

namespace Modules\Setting\app\Http\Controllers\Admin;

use App\Enums\Database\Setting\SettingItems;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Setting\app\Http\Requests\Admin\Setting\UpdateRequest;
use Modules\Setting\app\Models\Setting;

class SettingController extends Controller
{
    use HasJsonCommonResponseTrait;

    const EDIT_TITLE = 'تنظیمات - ویرایش';

    public function index()
    {
        $title = self::EDIT_TITLE;

        $settings = Setting::query()
            ->pluck('value', 'key')
            ->all();

        $allSettingKeys = SettingItems::asArray();

        foreach ($allSettingKeys as $item) {
            if (! array_key_exists($item, $settings)) {
                $settings[$item] = '';
                Setting::query()->create([
                    'key' => $item,
                    'value' => '',
                ]);
            }
        }

        return view('setting::admin.edit', compact('title', 'settings'));
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

        return $this->successUpdateResponse();
    }

    private function settingSet($key, $value)
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
