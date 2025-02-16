<?php

namespace App\Providers;

use App\Macros\ForeignKeyMacros;
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
                    'label' => self::getDescription($item),
                ];
            }

            return $data;
        });

        ForeignKeyMacros::register();
    }
}
