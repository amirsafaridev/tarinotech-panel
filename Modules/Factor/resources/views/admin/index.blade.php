@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Datepicker(),
        \App\Enums\Assets\StyleLoader::Select2(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <form class="row" action="{{ route('admin.factor.index') }}">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">{{ $title }}</div>
                    <div>
                        @can('ADMIN_FACTOR_CREATE')
                            <a class="btn btn-primary" href="{{ route('admin.factor.create') }}">ایجاد</a>
                        @endcan
                        <a class="btn btn-success datatable-export-button" href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" id="exportButton">خروجی Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('factor::admin.part.filter')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کارشناس</td>
                                <td>پروژه</td>
                                <td>مبلغ (ریال)</td>
                                <td>درگاه پرداخت</td>
                                <td>وضعیت</td>
                                <td>تاریخ پرداخت</td>
                                <td>ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کارشناس</td>
                                <td>پروژه</td>
                                <td>مبلغ (ریال)</td>
                                <td>درگاه پرداخت</td>
                                <td>وضعیت</td>
                                <td>تاریخ پرداخت</td>
                                <td>ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </tfoot>

                            <tbody>
                            @if($factors->isNotEmpty())
                                @foreach($factors as $factor)
                                    <tr>
                                        <td>{{ $factor->id }}</td>
                                        <td>{{ $factor->title }}</td>
                                        <td>{{ $factor->admin_first_name }} {{ $factor->admin_last_name }}</td>
                                        <td>{{ $factor->project_title }}</td>
                                        <td>{{ number_format($factor->final_price) }}</td>
                                        <td>{{ $factor->gateway ? \Modules\Factor\app\Enums\PaymentGateway::getDescription($factor->gateway) : 'نامشخص' }}</td>
                                        <td>{!! factorStatusRender($factor->status, $factor->is_confirm) !!}</td>
                                        <td>{{ $factor->paid_at ?  $factor->paid_at->toJalali()->format(formatJalaliDateTime()) : ''}}</td>
                                        <td>{{ $factor->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="btn btn-sm btn-warning" target="_blank" href="{{ route('admin.factor.edit',$factor->id) }}">{{ __('panel.action.edit') }}</a>
                                                <a class="btn btn-sm btn-info" target="_blank" href="{{ route('admin.factor.show',$factor->id) }}">{{ __('panel.action.show') }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">{{ $factors->total() }} رکورد یافت شد</div>
                        <div class="d-flex justify-content-center">
                            {{ $factors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            jalaliDatepicker.startWatch();
            $('select[name="status[]"]').select2({
                placeholder: "انتخاب وضعیت‌ها",
                allowClear: true,
                dir: "rtl"
            });
        })
    </script>
@endsection
