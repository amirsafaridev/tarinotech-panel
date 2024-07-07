<?php

use App\Enums\Database\Admin\TypeInsurance;
use App\Enums\Database\Admin\WorkLocation;
use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Company\CompanyType;
use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use App\Enums\Database\Setting\SettingItems;
use Modules\Contract\app\Enums\SignableStatus;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Log\app\Enums\LogEvents;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Enums\ProjectDesignBy;
use Modules\Project\app\Enums\SeoAgreementDuration;
use Modules\Project\app\Enums\SeoHostLocation;
use Modules\Project\app\Enums\WebFacility;
use Modules\Project\app\Enums\WebHostLocation;
use Modules\Project\app\Enums\WebLanguage;
use Modules\User\app\Enums\IrnicStatus;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;

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
        UserType::Primary => 'کارفرما',
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
        WebLanguage::ES => 'اسپانیایی',
        WebLanguage::PT => 'پرتغالی',
        WebLanguage::FR => 'فرانسوی',
        WebLanguage::RU => 'روسی',
        WebLanguage::DE => 'آلمانی',
        WebLanguage::TR => 'ترکی',
        WebLanguage::CN => 'چینی',
        WebLanguage::IN => 'هندی',
        WebLanguage::PK => 'پاکستانی',
        WebLanguage::RO => 'رومانیایی',
        WebLanguage::MS => 'مالایی',
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
        FactorStatus::Draft => 'پیش نویس',
        FactorStatus::PaidManual => 'پرداخت شده (دستی)',
        FactorStatus::PaidWithCheque => 'پرداخت با چک',
        FactorStatus::CustomerOffer => ' آفر مشتریان',
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

    LogNames::class => [
        LogNames::BLOG => 'بلاگ',
        LogNames::BLOG_CATEGORY => 'دسته بندی بلاگ',
        LogNames::ADDRESS => 'آدرس',
        LogNames::ADMIN => 'ادمین',
        LogNames::COMPANY => 'شرکت',
        LogNames::FACILITY => 'امکانات جانبی',
        LogNames::FACTOR => 'فاکتور',
        LogNames::FACTOR_STATUS => 'فاکتور - وضعیت خودکار',
        LogNames::FACTOR_ITEM => 'فاکتور ایتم',
        LogNames::FREE_DAY => 'تقویم تعطیلات',
        LogNames::LOGIN => 'ورود',
        LogNames::OTP_CODE => 'کد otp',
        LogNames::PACKAGE => 'پکیج',
        LogNames::PACKAGE_PRICE => 'قیمت وکیج',
        LogNames::PROJECT => 'پروژه',
        LogNames::PROJECT_ADS => 'پروژه ادوردز',
        LogNames::PROJECT_BASE => 'نوع پروژه',
        LogNames::PROJECT_FACILITY => 'امکانات جانبی پروژه',
        LogNames::PROJECT_SEO => 'پروژه سئو',
        LogNames::PROJECT_STATUS => 'وضعیت پروژه',
        LogNames::PROJECT_TYPE => 'نوع پروژه',
        LogNames::PROJECT_WEB => 'پروژه وب',
        LogNames::SALE_GOAL => 'هدف فروش',
        LogNames::SAMPLE_MESSAGE => 'پیام اماده',
        LogNames::SETTING => 'تنظیمات',
        LogNames::TRANSACTION_CATEGORY => 'دسته بندی تراکنش',
        LogNames::USER => 'کاربر',
    ],

    LogEvents::class => [
        LogEvents::CREATED => 'ایجاد',
        LogEvents::DELETED => 'حذف',
        LogEvents::UPDATED => 'به روز رسانی',
        LogEvents::RESTORED => 'بازگردانی',
    ],

    ChatStatus::class => [
        ChatStatus::Open => 'باز',
        ChatStatus::Close => 'بسته',
    ],

    PaymentGateway::class => [
        PaymentGateway::SEPEHR => 'سپهر (صادرات)',
        PaymentGateway::PAYPING => 'پی پینگ',
    ],

    SignableStatus::class => [
        SignableStatus::Pending => 'در انتظار',
        SignableStatus::Signed => 'امضاء شده',
        SignableStatus::Reject => 'رد شده',
    ],
];
