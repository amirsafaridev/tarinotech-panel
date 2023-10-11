<?php

namespace App\Enums\Database\Project;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebHostLocation extends Enum implements LocalizedEnum
{
    const IR = 'IR';

    const OUTSIDE = 'OUTSIDE';
}
