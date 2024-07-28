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
            line-height: 24px;
        }

        table {
            border-spacing: 0px;
            border-collapse: separate;
        }

        table td {
            border: 1px solid #d8d8d8;
        }

        td, th { /* table cells */
            padding: 5px;
        }

        .header-row-bg {
            background-color: #f4f4f4;
        }

        .text-center {
            text-align: center;
        }

        .p-title {
            font-weight: bold;
        }

        .f-bold {
            font-weight: bold;
        }
    </style>
</head>
<body dir="rtl">
@php
    use Modules\Factor\app\Enums\FactorStatus;

    $isSigned = $model->status === FactorStatus::Paid
        || ($model->status === FactorStatus::PaidManual && $model->is_confirm)
        || ($model->status === FactorStatus::CustomerOffer && $model->is_confirm);
@endphp

@if($model->is_official || $model->project?->user?->official_bill)
    @include('factor::admin.part.official_invoice',['factor'=>$model,'isSigned'=>$isSigned])
@else
    @include('factor::admin.part.unofficial_invoice',['factor'=>$model,'isSigned'=>$isSigned])
@endif
</body>
</html>
