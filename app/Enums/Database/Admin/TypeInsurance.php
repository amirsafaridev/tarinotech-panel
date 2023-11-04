<?php

namespace App\Enums\Database\Admin;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class TypeInsurance extends Enum implements LocalizedEnum
{
    const None = 0;

    const TaminInPerson = 1;

    const TaminInRemote = 2;
}
