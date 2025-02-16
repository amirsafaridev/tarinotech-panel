<?php

namespace App\Enums\Database\Facility;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FacilityStatus extends Enum implements LocalizedEnum
{
    const Cancel = 1;

    const Renewal = 2;
}
