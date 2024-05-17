<?php

namespace App\Enums\Database\Role;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PermissionName extends Enum implements LocalizedEnum
{
    const PROJECT_PRICE_SHOW = 'PROJECT_PRICE_SHOW';

    const PROJECT_PRICE_EDIT = 'PROJECT_PRICE_EDIT';

    const PROJECT_SELF = 'PROJECT_SELF';

    const PROJECT_WEB_STATUS_UPDATE = 'PROJECT_WEB_STATUS_UPDATE';
}
