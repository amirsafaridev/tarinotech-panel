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
                    <h3 class="card-title">تنخواه ها</h3>
                    @can('ADMIN_ADMIN_PERSONNEL_SALARY_CREATE')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.admin.personnel-salary.create') }}">ایجاد
                            تنخواه</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>

                                    <th>شناسه</th>
                                    <th>نام و نام‌خانوادگی</th>

                                    <th>مبلغ</th>
                                    <th>توضیحات</th>
                                    <th>تاریخ</th>

                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($personnelSalaries->isNotEmpty())
                                    @foreach ($personnelSalaries as $personnelSalary)
                                        <tr>
                                            <td>{{ $personnelSalary->id }}</td>

                                            <td>{{ $personnelSalary->user->fullname }}
                                            </td>

                                            <td>{{ number_format($personnelSalary->price) }}</td>
                                            <td>{{ $personnelSalary->description }}</td>

                                            <td>{{ $personnelSalary->date }}</td>

                                            <td>
                                                @can('ADMIN_ADMIN_PERSONNEL_SALARY_EDIT')
                                                    <a href="{{ route('admin.admin.personnel-salary.edit', $personnelSalary->id) }}"
                                                        class="btn btn-warning btn-sm">ویرایش</a>
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
