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
                    <h3 class="card-title">مبالغ متغیر </h3>
                    @can('ADMIN_ADMIN_VARIABLE_AMOUNT_CREATE')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.admin.variable-amount.create') }}">ایجاد جدول
                            مبلغ متغیر</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>

                                    <th>شناسه</th>
                                    <th>سمت</th>

                                    <th>تعداد واحد پایه P</th>
                                    <th>قیمت واحد اکسترا E</th>
                                    <th>مبلغ عملکرد ویژه</th>
                                    <th>مبنای پاداش بهره وری</th>
                                    <th>تاریخ</th>

                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($variableAmounts->isNotEmpty())
                                    @foreach ($variableAmounts as $variableAmount)
                                        <tr>
                                            <td>{{ $variableAmount->id }}</td>


                                            <td>{{ $variableAmount->jobTitle->title }}</td>
                                            <td>{{ number_format($variableAmount->base_units_count) }}</td>
                                            <td>{{ number_format($variableAmount->extra_units_amount) }}</td>
                                            <td>{{ number_format($variableAmount->performance_amount) }}</td>
                                            <td>{{ number_format($variableAmount->reward_basis) }}</td>

                                            <td>{{ $variableAmount->date }}</td>

                                            <td>
                                                @can('ADMIN_ADMIN_VARIABLE_AMOUNT_EDIT')
                                                    <a href="{{ route('admin.admin.variable-amount.edit', $variableAmount->id) }}"
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
