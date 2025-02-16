<?php

namespace Modules\Project\App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class RenewalStatus extends Enum implements LocalizedEnum
{
    const Pending = 1;

    const Pay = 2;
}
