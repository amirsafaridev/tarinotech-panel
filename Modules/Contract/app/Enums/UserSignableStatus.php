<?php

namespace Modules\Contract\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class UserSignableStatus extends Enum implements LocalizedEnum
{
    const Pending = 1;

    const Signed = 2;

    const Reject = 3;
}
