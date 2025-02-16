<?php

namespace Modules\Project\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ProjectBase extends Enum implements LocalizedEnum
{
    const Web = 1;

    const Seo = 2;

    const Ads = 3;
}
