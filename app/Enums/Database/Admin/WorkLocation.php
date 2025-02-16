<?php

namespace App\Enums\Database\Admin;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WorkLocation extends Enum implements LocalizedEnum
{
    const None = 0;

    const InPerson = 1;

    const Remote = 2;

    const Hybrid = 3;
}
