<?php

namespace App\Enums\Database\Company;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CompanyType extends Enum implements LocalizedEnum
{
    const LimitedResponsibility = 1;

    const PrivateEquity = 2;

    const PublicStock = 3;

    const Other = 4;
}
