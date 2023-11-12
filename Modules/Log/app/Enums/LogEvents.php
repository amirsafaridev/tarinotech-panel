<?php

namespace Modules\Log\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class LogEvents extends Enum implements LocalizedEnum
{
    const CREATED = 'created';

    const DELETED = 'deleted';

    const UPDATED = 'updated';

    const RESTORED = 'restored';
}
