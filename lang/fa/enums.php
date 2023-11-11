<?php

use App\Enums\Database\Admin\TypeInsurance;
use App\Enums\Database\Admin\WorkLocation;
use App\Enums\Database\Company\CompanyType;
use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use App\Enums\Database\Factor\FactorStatus;
use App\Enums\Database\Project\ProjectBase;
use App\Enums\Database\Project\ProjectDesignBy;
use App\Enums\Database\Project\SeoAgreementDuration;
use App\Enums\Database\Project\SeoHostLocation;
use App\Enums\Database\Project\WebFacility;
use App\Enums\Database\Project\WebHostLocation;
use App\Enums\Database\Project\WebLanguage;
use App\Enums\Database\Setting\SettingItems;
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

    SettingItems::class => [
        SettingItems::INSTAGRAM => 'اینستاگرام',
        SettingItems::TELEGRAM => 'تلگرام',
        SettingItems::TEL => 'تلفن',
        SettingItems::DIRECT_CONFIRM_PROJECT => 'تایید مستقیم پروژه',
        SettingItems::DIRECT_CONFIRM_FACTOR => 'تایید مستقیم فاکتور',
        SettingItems::META_TITLE => 'متا عنوان',
        SettingItems::META_DESCRIPTION => 'متا توضیحات',
        SettingItems::META_KEYWORDS => 'متا کلمات کلیدی',
        SettingItems::ADDRESS => 'آدرس',
        SettingItems::TARGET_YEAR => 'اهداف سالانه',
    ],

    WebHostLocation::class => [
        WebHostLocation::IR => 'ایران',
        WebHostLocation::OUTSIDE => 'خارج ایران',
    ],

    WebLanguage::class => [
        WebLanguage::FA => 'فارسی',
        WebLanguage::EN => 'انگلیسی',
        WebLanguage::AR => 'عربی',
    ],

    WebFacility::class => [
        WebFacility::ReceiveSMS => 'دریافت پنل پیامکی',
        WebFacility::ReceivePaymentGateway => 'دریافت درگاه پرداخت',
        WebFacility::ReceiveEnamad => 'دریافت اینماد',
        WebFacility::LoginMobile => 'لاگین با موبایل',
        WebFacility::OnlineChat => 'چت آنلاین',
        WebFacility::TicketSystem => 'سامانه تیکت',
    ],

    SeoHostLocation::class => [
        SeoHostLocation::IN_COMPANY => 'داخل شرکت',
        SeoHostLocation::OUT_COMPANY => 'خارج شرکت',
    ],

    ProjectDesignBy::class => [
        ProjectDesignBy::ByCompany => 'داخل شرکت',
        ProjectDesignBy::ByOutsideCompany => 'خارج شرکت',
    ],

    ProjectBase::class => [
        ProjectBase::Web => 'وب سایت',
        ProjectBase::Seo => 'سئو',
        ProjectBase::Ads => 'گوگل ادز',
    ],

    FactorStatus::class => [
        FactorStatus::Paid => 'پرداخت شده',
        FactorStatus::Pending => 'در انتظار پرداخت',
        FactorStatus::Expired => 'منقضی شده',
        FactorStatus::Lock => 'در حال پرداخت',
        FactorStatus::OnHold => 'نگه داشته شده',
    ],

    SeoAgreementDuration::class => [
        SeoAgreementDuration::OnYear => 'یک ساله',
        SeoAgreementDuration::SixMonth => 'شش ماهه',
    ],

    PriceType::class => [
        PriceType::None => 'ندارد',
        PriceType::Input => 'وارد کردن مبلغ',
        PriceType::Package => 'جزو پکیج انتخابی',
    ],

    WorkCycle::class => [
        WorkCycle::None => 'ندارد',
        WorkCycle::InputDate => 'ثبت تاریخ',
        WorkCycle::Yearly => 'سالانه',
    ],

    FinancialCycle::class => [
        FinancialCycle::None => 'ندارد',
        FinancialCycle::InputPrice => 'درج قیمت',
        FinancialCycle::CalcFromPackage => 'محاسبه روی پکیج',
        FinancialCycle::InputLater => 'بعدا درج میشود',
        FinancialCycle::TwentyPercentCreationPrice => '۲۰ درصد قیمت ایجاد',
    ],

    TypeInsurance::class => [
        TypeInsurance::None => 'ندارد',
        TypeInsurance::TaminInPerson => 'تامین اجتماعی - حضوری',
        TypeInsurance::TaminInRemote => 'تامین اجتماعی - ریموت',
    ],

    WorkLocation::class => [
        WorkLocation::None => 'ندارد',
        WorkLocation::Remote => 'ریموت',
        WorkLocation::Hybrid => 'هیبریدی',
        WorkLocation::InPerson => 'حضوری',
    ],
];
