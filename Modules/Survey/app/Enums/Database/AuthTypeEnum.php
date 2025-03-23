<?php

namespace Modules\Survey\app\Enums\Database;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class AuthTypeEnum extends Enum implements LocalizedEnum
{
    const Admin = 1;

    const Web = 2;
}
