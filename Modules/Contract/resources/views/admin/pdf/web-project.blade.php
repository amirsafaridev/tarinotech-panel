<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/fonts.css') }}">
    <style>
        body {
            font-family: iransanse, serif;
            direction: rtl;
            font-size: 12px;
            line-height: 19px;
        }
        .sign-padding{
            padding: 10px;
        }
        .text-left{
            text-align: left;
        }
        p{
            text-align: justify;
        }
    </style>
</head>
<body dir="rtl">
<div style="padding-left: 35px;padding-right: 35px">
    @if($model->project?->user?->person_type === \Modules\User\app\Enums\PersonType::Legal)
        <p>این موافقتنامه در تاریخ (بحروف) ALPHA_DATE فیمابین شرکت USER_COMPANY به شناسه ملی USER_COMPANY_IDENTIFY و شماره ثبت USER_COMPANY_REGISTER_ID به نشانی USER_ADDRESS و شماره تماس USER_TEL و ایمیل USER_EMAIL به نمایندگی USERNAME به کدملی USER_NATIONAL به سمت USER_COMPANY_POSITION كه از این پس كارفرما نامیده می‌شود از یك سو و شرکت برخط نگاران جهان ارتباط با نام تجاری تارینوتک به شناسه ملی  14005743726 و کد اقتصادی 411513734335 به آدرس تهران، بلوار میرداماد، رو به روی بانک مرکزی، پلاک163، واحد12 و شماره تماس 02122223350 و ایمیلcontract@tarinotech.com  به نمایندگی معین تقی زاده به کد ملی 0371103827 به سمت مدیرعامل كه از این پس مجری نامیده می‌شود طبق مقررات و شرایطی كه در اسناد و مدارك این قرارداد درج شده است منعقد می‌گردد.</p>
    @else
        <p>این موافقتنامه در تاریخ (بحروف) ALPHA_DATE فیمابین آقای / خانم USERNAME  به کدملی USER_NATIONAL به نشانی USER_ADDRESS و شماره تماس USER_TEL و ایمیل USER_EMAIL كه از این پس كارفرما نامیده می‌شود از یك سو و شرکت برخط نگاران جهان ارتباط با نام تجاری تارینوتک به شناسه ملی  14005743726 و کد اقتصادی 411513734335 به آدرس تهران، بلوار میرداماد، رو به روی بانک مرکزی، پلاک163، واحد12 و شماره تماس 02122223350 و ایمیلcontract@tarinotech.com  به نمایندگی معین تقی زاده به کد ملی 0371103827 به سمت مدیرعامل كه از این پس مجری نامیده می‌شود طبق مقررات و شرایطی كه در اسناد و مدارك این قرارداد درج شده است منعقد می‌گردد.</p>
    @endif

    {!! $contractText !!}

    @if($model->project?->user?->person_type === \Modules\User\app\Enums\PersonType::Legal)
        <p>این قرار داد در تاریخ (بحروف) ALPHA_DATE در 14 ماده در دو نسخه یکسان به انضمام برگه سفارش که هر یک به‌ تنهایی اعتبار یکسان دارند تنظیم و به امضای معین تقی زاده به سمت نماینده مجری و USERNAME به سمت نماینده كارفرما رسید.</p>
    @else
        <p>این قرار داد در تاریخ (بحروف) ALPHA_DATE در 14 ماده در دو نسخه یکسان به انضمام برگه سفارش که هر یک به‌ تنهایی اعتبار یکسان دارند تنظیم و به امضای معین تقی زاده به سمت نماینده مجری و USERNAME به سمت کارفرما رسید.</p>
    @endif

    @if($model?->package)
        {!! $model?->package->contract_attachment !!}
    @endif

    @if($model?->project)
        {!! $model?->project->contract_attachment !!}
    @endif
</div>
</body>
</html>
