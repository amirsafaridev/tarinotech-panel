<?php

use Modules\Admin\app\Models\Admin;
use Modules\Log\app\Enums\LogEvents;
use Modules\Log\app\Enums\LogNames;
use Modules\User\app\Models\User;

if (! function_exists('getEventName')) {

    function getEventName(string $event): string
    {
        return LogNames::getDescription($event);
    }

}

if (! function_exists('getEventType')) {
    function getEventType(?string $event): string
    {
        $eventColors = [
            LogEvents::CREATED => 'success',
            LogEvents::UPDATED => 'primary',
            LogEvents::DELETED => 'danger',
            LogEvents::RESTORED => 'secondary',
            LogEvents::EDITED => 'warning',
        ];

        $color = $eventColors[$event] ?? 'default';

        return sprintf('<span class="btn btn-sm btn-outline-%s">%s</span>', $color, LogEvents::getDescription($event));
    }
}

if (! function_exists('getEventCauserType')) {
    function getEventCauserType(string $type): string
    {
        return match ($type) {
            Admin::class => 'پرسنل',
            User::class => 'کاربر',
            default => 'نا مشخص',
        };
    }
}

if (! function_exists('getCauserProfile')) {
    function getCauserProfile(Admin|User $causer): string
    {
        $routeName = ($causer instanceof Admin) ? 'admin.admin.show' : 'admin.user.show';

        return sprintf('<a class="btn btn-outline-info btn-sm" target="_blank" href="%s">%s</a>',
            route($routeName, $causer->id),
            $causer->first_name.' '.$causer->last_name
        );
    }
}
if (! function_exists('formatAttributeName')) {
    function formatAttributeName($attribute): string
    {
        return ucfirst(str_replace('_', ' ', $attribute));
    }
}
