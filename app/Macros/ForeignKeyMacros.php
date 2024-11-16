<?php

namespace App\Macros;

use Illuminate\Database\Schema\Blueprint;

class ForeignKeyMacros
{
    /**
     * Register the dropForeignSafe macro.
     */
    public static function register(): void
    {
        Blueprint::macro('dropForeignSafe', function ($args) {
            if (app()->runningUnitTests()) {
                // Do nothing when running unit tests
            } else {
                $this->dropForeign($args);
            }
        });
    }
}
