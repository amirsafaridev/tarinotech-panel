<?php

namespace App\Enums\Database\Project;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SeoHostLocation extends Enum implements LocalizedEnum
{
    const IN_COMPANY = 'IN_COMPANY';

    const OUT_COMPANY = 'OUT_COMPANY';
}
