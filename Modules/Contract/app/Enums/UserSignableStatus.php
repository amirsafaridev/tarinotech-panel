<?php

namespace Modules\Contract\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class UserSignableStatus extends Enum implements LocalizedEnum
{
    const Pending = 1;

    const Uploaded = 2;

    const Reject = 3;

    const Accepted = 4;
}
