<?php

namespace App\Enums\Database\Facility;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FacilityWorkCycle extends Enum implements LocalizedEnum
{
    const None = 1;

    const Yearly = 2;

    const InputDate = 3;
}
