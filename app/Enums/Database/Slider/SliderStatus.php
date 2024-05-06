<?php

namespace App\Enums\Database\Slider;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SliderStatus extends Enum implements LocalizedEnum
{
    const Active = 1;

    const DeActive = 0;
}
