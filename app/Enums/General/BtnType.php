<?php

namespace App\Enums\General;

use BenSampo\Enum\Enum;

/**
 * @method static static Warning()
 * @method static static Info()
 * @method static static Danger()
 * @method static static Success()
 */
final class BtnType extends Enum
{
    const Warning = 1;

    const Info = 2;

    const Danger = 3;

    const Success = 4;
}
