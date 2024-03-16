@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    <style>
        .print-main-table{
            padding: 0;
            margin: 0;
            width: 100%;
            font-size: 16px;
        }
        .print-main-table table, .print-main-table th, .print-main-table td {
            border: 1px solid #686868;
        }
        .print-main-table td{
            padding: 10px;
        }
        .print-main-table p{
            padding: 0;
            margin: 0;
        }
        @media print {
            .jumps-prevent{
                display: none;
            }
            .rtl .app-content{
                margin: 0;
            }
            .hide-in-print{
                display: none !important;
            }

            .print-main-table .p-title{
                font-weight: bold;
                font-size: 18px;
            }

            .print-main-table .f-bold{
                font-weight: bold;
            }

            .print-main-table .header-row-bg{
                background-color: #f3f3f3;
            }

            .print-main-table table, .print-main-table th, .print-main-table td {
                border: 1px solid #686868;
                color: black;
            }
            .print-main-table td{
                padding: 10px;
            }

            .card , .app-content , .page-main{
                background-color: white !important;
            }

            .side-app {
                padding: 0 !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">نمایش</li>
            </ol>
        </div>
    </div>

    <div class="row hide-in-print">
        <div class="col-12 col-md-6 mb-3">
            @include('factor::admin.part.card-info',['factor' => $factor])
        </div>

        <div class="col-12 col-md-6 mb-3">
            @include('factor::admin.part.card-project',['factor' => $factor])
            @if($factor->items->isNotEmpty())
                @include('factor::admin.part.card-items',['items' => $factor->items])
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center hide-in-print">
                    <span class="bold">پرینت فاکتور</span>
                    <button id="btn_print" type="button" class="btn btn-sm btn-success">پرینت فاکتور</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if($factor->project)
                            <table class="print-main-table">
                                <tbody>
                                <tr class="header-row-bg">
                                    <td colspan="3">
                                        <p class="text-center p-title">صورت حساب الکترونیکی فروش خدمات شرکت برخط نگاران جهان ارتباط</p>
                                    </td>
                                    <td colspan="1">
                                        <p class="text-center p-title">{{ $factor->created_at->toJalali()->format(formatJalaliDate()) }}</p>
                                    </td>
                                </tr>
                                <tr class="header-row-bg">
                                    <td colspan="4">
                                        <p class="text-right p-title">مشخصات فروشنده</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%">
                                        <p>
                                            <span>شماره اقتصادی :</span>
                                            <span>411513734335</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>شماره ثبت / شماره ملی :</span>
                                            <span>489741</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>شناسه ملی :</span>
                                            <span>14005743726</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>کدپستی :</span>
                                            <span>1911613635</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" colspan="2">
                                        <p>
                                            <span>نام شخص حقیقی/حقوقی :</span>
                                            <span>معین تقی زاده</span>
                                        </p>
                                    </td>
                                    <td width="50%" colspan="2">
                                        <p>
                                            <span>شماره تماس :</span>
                                            <span>02178513</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%">
                                        <p>
                                            <span>شماره پرونده گمرکی :</span>
                                            <span>-</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>کد گمرک محل اظهار :</span>
                                            <span>-</span>
                                        </p>
                                    </td>
                                    <td width="50%" colspan="2">
                                        <p>
                                            <span>شناسه یکتای ثبت قرارداد :</span>
                                            <span>-</span>
                                        </p>
                                    </td>

                                </tr>

                                <tr>
                                    <td colspan="4">
                                        <p>آدرس</p>
                                        <p>تهران، شهر تهران، داوودیه، خیابان شهید امیر سهیل تبریزیان ، خیابان آسایی، پلاک ۲ طبقه ۳ واحد ۱۲</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="header-row-bg">
                                        <p class="text-right p-title">مشخصات خریدار</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%">
                                        <p>
                                            <span>شماره اقتصادی :</span>
                                            <span>-</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>شماره / شماره ملی :</span>
                                            <span>{{ $factor->project->user->national_id }}</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>شناسه ملی :</span>
                                            <span>-</span>
                                        </p>
                                    </td>
                                    <td width="25%">
                                        <p>
                                            <span>کدپستی :</span>
                                            <span>{{ $factor->project->user->address?->postal_code }}</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" colspan="2">
                                        <p>
                                            <span>نام شخص {{ \Modules\User\app\Enums\PersonType::getDescription($factor->project->user->person_type) }} :</span>
                                            <span>{{ $factor->project->user->first_name }} {{ $factor->project->user->last_name }}</span>
                                        </p>
                                    </td>
                                    <td width="50%" colspan="2">
                                        <p>
                                            <span>نام بنگاه اقتصادی :</span>
                                            <span>{{ $factor->project->user->comany?->title }}</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p>آدرس</p>
                                        <p>{{ $factor->project->user->address?->address }}</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">
                                        <p class="text-right">مشحصات کالا / خدمت مورد معامله</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4">
                                        <table class="print-main-table inner-table">
                                            <tbody>
                                            <tr>
                                                <td>ردیف</td>
                                                <td>شرح کالا / خدمات</td>
                                                <td>واحد اندازه گیری</td>
                                                <td>تعداد / مقدار</td>
                                                <td>مبلغ واحد (ریال)</td>
                                                <td>نوع ارز</td>
                                                <td>مبلغ تخفیف</td>
                                                <td>نوع مالیات بر ارزش افزوده</td>
                                                <td>مبلغ مالیات بر ارزش افزوده</td>
                                                <td>مبلغ کالا / خدمات</td>
                                            </tr>
                                            @php
                                                $totalDiscount = 0;
                                                $totalTaxAmount = 0;
                                                $totalPrice = 0;
                                            @endphp

                                            @if($factor->items->isNotEmpty())
                                                @foreach($factor->items as $item)
                                                    @php
                                                        $totalDiscount += $item->discount;
                                                        $totalTaxAmount += $item->tax_amount;
                                                        $totalPrice += $item->final_price;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->index + 1}}</td>
                                                        <td>{{ $item->title }}</td>
                                                        <td>عدد</td>
                                                        <td>1</td>
                                                        <td>{{ number_format($item->price) }}</td>
                                                        <td>ریال (ایران)</td>
                                                        <td>{{ number_format($item->discount) }}</td>
                                                        <td>9.00</td>
                                                        <td>{{ number_format($item->tax_amount) }}</td>
                                                        <td>{{ number_format($item->final_price) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <tr>
                                                <td colspan="6">جمع کل</td>
                                                <td class="f-bold">{{ number_format($totalDiscount) }}</td>
                                                <td class="f-bold">-</td>
                                                <td class="f-bold">{{ number_format($totalTaxAmount) }}</td>
                                                <td class="f-bold">{{ number_format($totalPrice) }}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="6">مبلغ نهایی</td>
                                                <td colspan="4" class="f-bold">{{ number_format($totalPrice) }}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.factor.index') }}');
            $('#btn_print').click(function (){
                printMe();
            })
        });
    </script>
@endsection
