<?php

namespace App\Enums\Database\Project;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SeoAgreementDuration extends Enum implements LocalizedEnum
{
    const OnYear = 365;

    const SixMonth = 180;
}
