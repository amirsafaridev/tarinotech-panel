<?php

namespace Modules\User\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class IrnicStatus extends Enum implements LocalizedEnum
{
    const HasIt = 1;

    const DoesntHaveMade = 2;

    const DoesNotNeed = 3;
}
