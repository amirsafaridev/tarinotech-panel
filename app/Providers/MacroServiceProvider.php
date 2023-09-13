<?php

namespace App\Providers;

use BenSampo\Enum\Enum;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Enum::macro('asApi', function () {
            $data = [];

            foreach (self::asArray() as $key => $item) {
                $data[] = [
                    'key' => $item,
                    'value' => $key,
                    'description' => self::getDescription($item),
                    'select' => false,
                ];
            }

            return $data;
        });
    }
}
