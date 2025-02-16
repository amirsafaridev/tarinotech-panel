<?php

namespace App\Enums\Database\Role;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class RoleName extends Enum implements LocalizedEnum
{
    const SUPER_ADMIN = 1;
}
