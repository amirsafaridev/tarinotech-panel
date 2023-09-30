<?php

use App\Enums\Database\Company\CompanyType;
use App\Enums\Database\User\IrnicStatus;
use App\Enums\Database\User\PersonType;
use App\Enums\Database\User\UserType;

return [
    IrnicStatus::class => [
        IrnicStatus::HasIt => 'دارد',
        IrnicStatus::DoesntHaveMade => 'ندارد - ساخته شود',
        IrnicStatus::DoesNotNeed => 'نیاز ندارد',
    ],
    PersonType::class => [
        PersonType::Legal => 'حقوقی',
        PersonType::Person => 'حقیقی',
    ],

    CompanyType::class => [
        CompanyType::Other => 'سایر',
        CompanyType::LimitedResponsibility => 'مسئولیت محدود',
        CompanyType::PrivateEquity => 'سهامی خاص',
        CompanyType::PublicStock => 'سهامی عام',
    ],

    UserType::class => [
        UserType::Presenter => 'نماینده',
        UserType::Primary => 'مشتری',
    ],
];
