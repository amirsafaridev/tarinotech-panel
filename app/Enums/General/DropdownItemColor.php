<?php

namespace App\Enums\General;

use BenSampo\Enum\Enum;

/**
 * @method static static Warning()
 * @method static static Info()
 * @method static static Danger()
 * @method static static Success()
 */
final class DropdownItemColor extends Enum
{
    const Warning = 1;

    const Info = 2;

    const Danger = 3;

    const Success = 4;

    public static function getBootstrapClass(int $value): string
    {
        switch ($value) {
            case self::Warning:
                return 'warning';
            case self::Info:
                return 'info';
            case self::Danger:
                return 'danger';
            case self::Success:
                return 'success';
            default:
                return 'primary';
        }
    }
}
