<?php

namespace App\Enums\Database\Facility;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FacilityFinancialCycle extends Enum implements LocalizedEnum
{
    const None = 1;

    const InputPrice = 2;

    const CalcFromPackage = 3;

    const InputLater = 4;

    const TwentyPercentCreationPrice = 5;
}
