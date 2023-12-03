<?php

namespace Modules\Project\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ProjectDesignBy extends Enum implements LocalizedEnum
{
    const ByCompany = 1;

    const ByOutsideCompany = 2;
}
