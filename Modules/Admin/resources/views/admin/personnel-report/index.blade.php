@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [\App\Enums\Assets\StyleLoader::DataTable()],
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
                    <h3 class="card-title">لیست درخواست‌های مرخصی</h3>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>
                                    <th>نوع مرخصی</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>مرخصی برای تاریخ</th>
                                    <th>به مدت</th>
                                    <th>وضعیت</th>
                                    <th>مرخصی اضطراری</th>
                                    <th>تعیین کننده وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($personnelReports->isNotEmpty())
                                    @foreach ($personnelReports as $personnelReport)
                                        <tr>
                                            <td>{{ $personnelReport->type_text }}</td>
                                            <td>{{ verta($personnelReport->created_at)->format('Y/m/d H:i') }}</td>
                                            <td>
                                                @if ($personnelReport->type === 'daily')
                                                    {{ verta($personnelReport->start_date)->format('Y/m/d') }} تا
                                                    {{ verta($personnelReport->end_date)->format('Y/m/d') }}
                                                @else
                                                    {{ verta($personnelReport->date)->format('Y/m/d') }} از
                                                    {{ $personnelReport->start_time }} تا {{ $personnelReport->end_time }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($personnelReport->type === 'daily')
                                                    {{ $personnelReport->start_date->diffInDays($personnelReport->end_date) + 1 }}
                                                    روز
                                                @else
                                                    {{ $personnelReport->total_hours }} ساعت
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $personnelReport->status === 'approved' ? 'success' : ($personnelReport->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ $personnelReport->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $personnelReport->is_emergency ? 'بله' : 'خیر' }}
                                            </td>
                                            <td>
                                                @if ($personnelReport->status !== 'pending')
                                                    {{ $personnelReport->diterminantUser->fullname }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $personnelReport->id }}">
                                                    مشاهده
                                                </button>
                                                @if ($personnelReport->status === 'pending')
                                                    @can('ADMIN_ADMIN_PERSONNEL_REPORT_APPROVE')
                                                        <a href="{{ route('admin.admin.personnel-report.approve', $personnelReport->id) }}"
                                                            class="btn btn-success btn-sm">
                                                            تایید
                                                        </a>
                                                    @endcan
                                                    @can('ADMIN_ADMIN_PERSONNEL_REPORT_REJECT')
                                                        <a href="{{ route('admin.admin.personnel-report.reject', $personnelReport->id) }}"
                                                            class="btn btn-danger btn-sm">
                                                            رد
                                                        </a>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal for viewing leave details -->
                                        <div class="modal fade" id="viewModal{{ $personnelReport->id }}" tabindex="-1"
                                            aria-labelledby="viewModalLabel{{ $personnelReport->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="viewModalLabel{{ $personnelReport->id }}">
                                                            جزئیات درخواست مرخصی</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">نام و نام خانوادگی:</div>
                                                            <div class="col-md-8">{{ $personnelReport->user->fullname }}
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">نوع مرخصی:</div>
                                                            <div class="col-md-8">{{ $personnelReport->type_text }}</div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">تاریخ درخواست:</div>
                                                            <div class="col-md-8">
                                                                {{ verta($personnelReport->created_at)->format('Y/m/d H:i') }}
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">زمان مرخصی:</div>
                                                            <div class="col-md-8">
                                                                @if ($personnelReport->type === 'daily')
                                                                    {{ verta($personnelReport->start_date)->format('Y/m/d') }}
                                                                    تا
                                                                    {{ verta($personnelReport->end_date)->format('Y/m/d') }}
                                                                @else
                                                                    {{ verta($personnelReport->date)->format('Y/m/d') }} از
                                                                    {{ $personnelReport->start_time }} تا
                                                                    {{ $personnelReport->end_time }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">مدت مرخصی:</div>
                                                            <div class="col-md-8">
                                                                @if ($personnelReport->type === 'daily')
                                                                    {{ $personnelReport->start_date->diffInDays($personnelReport->end_date) + 1 }}
                                                                    روز
                                                                @else
                                                                    {{ $personnelReport->total_hours }} ساعت
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">وضعیت:</div>
                                                            <div class="col-md-8">
                                                                <span
                                                                    class="badge bg-{{ $personnelReport->status === 'approved' ? 'success' : ($personnelReport->status === 'rejected' ? 'danger' : 'warning') }}">
                                                                    {{ $personnelReport->status_text }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 fw-bold">مرخصی اضطراری:</div>
                                                            <div class="col-md-8">
                                                                {{ $personnelReport->is_emergency ? 'بله' : 'خیر' }}
                                                            </div>
                                                        </div>
                                                        @if ($personnelReport->description)
                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fw-bold">توضیحات:</div>
                                                                <div class="col-md-8">{{ $personnelReport->description }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if ($personnelReport->status !== 'pending')
                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fw-bold">تعیین کننده وضعیت:</div>
                                                                <div class="col-md-8">
                                                                    {{ $personnelReport->diterminantUser->fullname }}</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">بستن</button>
                                                    </div>
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
    @include('admin.partial.loader.script', [
        'load' => [\App\Enums\Assets\ScriptLoader::DataTable()],
    ])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.datatable_offline')
@endsection
