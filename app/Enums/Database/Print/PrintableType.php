<?php

namespace App\Enums\Database\Print;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PrintableType extends Enum implements LocalizedEnum
{
    const ProjectWeb = 'projectWeb';

    const ProjectSeo = 'projectSeo';

    const ProjectAds = 'projectAds';

    const Factor = 'factor';
}
