@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::DataTable(),
        \App\Enums\Assets\StyleLoader::Alert(),

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

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">لیست درخواست‌های مرخصی</h3>
                    @can('ADMIN_ADMIN_PERSONNEL_REPORT_CREATE')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.admin.personnel-report.create') }}">
                            <i class="fa fa-plus"></i> ثبت مرخصی
                        </a>
                    @endcan
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
                                <th>تعیین کننده وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($personnelReports->isNotEmpty())
                                @foreach($personnelReports as $personnelReport)
                                    <tr>
                                        <td>{{ $personnelReport->type_text }}</td>
                                        <td>{{ verta($personnelReport->created_at)->format('Y/m/d H:i') }}</td>
                                        <td>
                                            @if($personnelReport->type === 'daily')
                                                {{ verta($personnelReport->start_date)->format('Y/m/d') }} تا {{ verta($personnelReport->end_date)->format('Y/m/d') }}
                                            @else
                                                {{ verta($personnelReport->date)->format('Y/m/d') }} از {{ $personnelReport->start_time }} تا {{ $personnelReport->end_time }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($personnelReport->type === 'daily')
                                                {{ $personnelReport->start_date->diffInDays($personnelReport->end_date) + 1 }} روز
                                            @else
                                                {{ $personnelReport->total_hours }} ساعت
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $personnelReport->status === 'approved' ? 'success' : ($personnelReport->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ $personnelReport->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($personnelReport->status !== 'pending')
                                                {{ $personnelReport->user->first_name . ' ' . $personnelReport->user->last_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($personnelReport->status === 'pending')
                                                <a href="{{ route('admin.admin.personnel-report.edit', $personnelReport->id) }}" 
                                                   class="btn btn-warning btn-sm" 
                                                   title="ویرایش">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="حذف"
                                                        onclick="confirmDelete()">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <form id="deleteItem" action="{{ route('admin.admin.personnel-report.destroy', $personnelReport->id) }}"
                                        method="post" class="form-inline">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::DataTable(),
        \App\Enums\Assets\ScriptLoader::Alert(),

    ]])
    
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.datatable_offline')
 
@endsection
