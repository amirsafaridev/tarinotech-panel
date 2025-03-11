@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', ['load' => [\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">فیش های حقوقی</h3>

                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>

                                    <th>شناسه</th>
                                    <th>تاریخ ایجاد</th>

                                    <th>ماه</th>
                                    <th>روز</th>
                                    <th>وضعیت</th>

                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($payslips->isNotEmpty())
                                    @foreach ($payslips as $payslip)
                                        <tr>
                                            <td>{{ $payslip->id }}</td>


                                            <td>{{ $payslip->created_at->toJalali()->format('d F Y') }}</td>
                                            <td>{{ $payslip->created_at->toJalali()->format('F') }}</td>
                                            <td>{{ $payslip->created_at->toJalali()->format('d') }}</td>
                                            <td>{{ $payslip->status === 0 ? 'در انتظار تایید' : 'تایید شده' }}
                                            </td>

                                            <td>
                                                @can('ADMIN_PERSONNEL_PAYSLIP_SHOW')
                                                    <a href="{{ route('admin.personnel.payslip.show', $payslip->id) }}"
                                                        class="btn btn-warning btn-sm">مشاهده</a>
                                                @endcan
                                                @can('ADMIN_PERSONNEL_PAYSLIP_SHOW')
                                                <a href="{{ route('admin.personnel.payslip.show', $payslip->id) }}"
                                                    class="btn btn-success btn-sm">تایید</a>
                                            @endcan @can('ADMIN_PERSONNEL_PAYSLIP_SHOW')
                                            <a href="{{ route('admin.personnel.payslip.show', $payslip->id) }}"
                                                class="btn btn-danger btn-sm">عدم تایید</a>
                                        @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script', ['load' => [\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('admin.partial.datatable_offline')
@endsection
