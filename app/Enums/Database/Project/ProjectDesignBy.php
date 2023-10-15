<?php

namespace App\Enums\Database\Project;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ProjectDesignBy extends Enum implements LocalizedEnum
{
    const ByCompany = 1;

    const ByOutsideCompany = 2;
}
