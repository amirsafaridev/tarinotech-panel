<?php

namespace Modules\Factor\App\Traits;

use Modules\Factor\app\Enums\PaymentGateway;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Models\User;

trait DeterminesBillingDetailsTrait
{
    /**
     * Check if the user associated with the project is official.
     */
    public function isOfficialUser(User $user): bool
    {
        return $user->person_type === PersonType::Legal || $user->official_bill;
    }

    /**
     * Determine the payment gateway for a given project.
     */
    public function determinePaymentGateway(User $user): string
    {
        return $this->isOfficialUser($user)
            ? PaymentGateway::SEPEHR
            : PaymentGateway::PAYPING;
    }
}
