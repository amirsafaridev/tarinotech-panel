<?php

namespace Modules\Ticket\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class TriggerEnum extends Enum implements LocalizedEnum
{
    const TICKET_CREATED = 'ticket_created';

    const SUPPORT_OPENED_TICKET = 'support_opened_ticket';

    const CUSTOMER_REPLIED = 'customer_replied';

    const SUPPORT_REPLIED = 'support_replied';

    const CUSTOMER_RESPONSE_TIMEOUT = 'customer_response_timeout';

    const RESOLUTION_CONFIRMATION_TIMEOUT = 'resolution_confirmation_timeout';
}
