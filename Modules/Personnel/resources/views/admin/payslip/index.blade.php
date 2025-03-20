@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [\App\Enums\Assets\StyleLoader::DataTable(), \App\Enums\Assets\StyleLoader::Toast()],
    ])
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
                                            <td>
                                                @if ($payslip->status === 0)
                                                    <span class="badge bg-warning">در انتظار تایید</span>
                                                @elseif ($payslip->status === 1)
                                                    <span class="badge bg-success">تایید شده</span>
                                                @else
                                                    <span class="badge bg-danger">رد شده</span>
                                                @endif
                                            </td>
                                            </td>

                                            <td>
                                                <div class="dropdown">

                                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        {{ __('panel.action.manage') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @can('ADMIN_PERSONNEL_PAYSLIP_SHOW')
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.personnel.payslip.show', $payslip->id) }}">{{ __('panel.action.show') }}</a>
                                                        @endcan
                                                        @if ($payslip->status === 0)
                                                            @can('ADMIN_PERSONNEL_PAYSLIP_APPROVED')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.personnel.payslip.approved', $payslip->id) }}">{{ __('panel.action.approve') }}</a>
                                                            @endcan

                                                            @can('ADMIN_PERSONNEL_PAYSLIP_CANCELED')
                                                                <button type="button"
                                                                    class="dropdown-item"
                                                                    data-payslip-id="{{ $payslip->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#rejectModal-{{ $payslip->id }}">
                                                                    {{ __('panel.action.cancel') }}
                                                                </button>
                                                            @endcan
                                                        @endif

                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal-{{ $payslip->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">رد درخواست</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form class="request-form forms-sample" method="post"
                                                        action="{{ route('admin.personnel.payslip.canceled', $payslip->id) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <x-admin.textarea title="دلیل رد درخواست"
                                                                identify="reject_reason" rows="3" />
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">انصراف</button>
                                                            <x-admin.button title="ثبت" />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
    @include('admin.partial.request')
    @include('admin.partial.loader.script', ['load' => [\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('admin.partial.datatable_offline')
@endsection
