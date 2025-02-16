<?php

use Modules\Factor\app\Enums\FactorStatus;

if (! function_exists('calcPercentOfPrice')) {
    function calcPercentOfPrice(float $percent, int $price): int
    {
        if ($percent < 0 || $percent > 100) {
            throw new InvalidArgumentException('Percentage must be between 0 and 100.');
        }

        return (int) round(($percent / 100) * $price);
    }
}

if (! function_exists('factorStatusRender')) {
    function factorStatusRender(int $status, ?int $isConfirm = null): string
    {
        $statusTitle = FactorStatus::getDescription($status);
        $badgeClass = '';

        switch ($status) {
            case FactorStatus::Paid:
                $badgeClass = 'success';
                break;

            case FactorStatus::PaidManual:
            case FactorStatus::CustomerOffer:
            case FactorStatus::PaidWithCheque:
                $badgeClass = $isConfirm ? 'success' : 'gray';
                break;

            case FactorStatus::Pending:
                $badgeClass = 'primary';
                break;

            case FactorStatus::Expired:
                $badgeClass = 'danger';
                break;

            case FactorStatus::Lock:
            case FactorStatus::Draft:
            case FactorStatus::OnHold:
                $badgeClass = 'info';
                break;
        }

        if ($badgeClass) {
            return sprintf('<span class="badge bg-%s">%s</span>', $badgeClass, $statusTitle);
        }

        return '';
    }
}
