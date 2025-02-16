<?php

namespace Modules\Project\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebDomain extends Enum implements LocalizedEnum
{
    const IR = 'IR';

    const COM = 'COM';

    const NET = 'NET';

    const EDU = 'EDU';

    const ME = 'ME';
}
