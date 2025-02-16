<?php

namespace App\Enums\Database\Setting;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SettingItems extends Enum implements LocalizedEnum
{
    const META_TITLE = 'meta_title';

    const META_DESCRIPTION = 'meta_description';

    const META_KEYWORDS = 'meta_keywords';

    const ADDRESS = 'address';

    const INSTAGRAM = 'instagram';

    const TELEGRAM = 'telegram';

    const TEL = 'tel';

    const TARGET_YEAR = 'target_year';

    const DIRECT_CONFIRM_PROJECT = 'direct_confirm_project';

    const DIRECT_CONFIRM_FACTOR = 'direct_confirm_factor';
}
