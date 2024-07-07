<?php

namespace Modules\Contract\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PlaceHolderKeys extends Enum implements LocalizedEnum
{
    const ALPHA_DATE = 'ALPHA_DATE';

    const USER_COMPANY = 'USER_COMPANY';

    const USER_NATIONAL = 'USER_NATIONAL';

    const USER_COMPANY_REGISTER_ID = 'USER_COMPANY_REGISTER_ID';

    const USER_ADDRESS = 'USER_ADDRESS';

    const USER_TEL = 'USER_TEL';

    const USER_EMAIL = 'USER_EMAIL';

    const USERNAME = 'USERNAME';

    const DOCUMENT_ID = 'DOCUMENT_ID';

    const USER_COMPANY_POSITION = 'USER_COMPANY_POSITION';

    const PROJECT_TYPE = 'PROJECT_TYPE';

    const PROJECT_PRICE = 'PROJECT_PRICE';

    const PROJECT_TIME_WORK = 'PROJECT_TIME_WORK';
}
