<?php

namespace App\Enums\Database\Facility;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FacilityPriceType extends Enum implements LocalizedEnum
{
    const None = 1;

    const Package = 2;

    const Input = 3;
}
