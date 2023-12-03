<?php

namespace Modules\Project\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebHostLocation extends Enum implements LocalizedEnum
{
    const IR = 'IN_IRAN';

    const OUTSIDE = 'OUT_IRAN';
}
