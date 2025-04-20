@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
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
                    <h3 class="card-title">وضعیت‌های تیکت</h3>
                    @can('ADMIN_TICKET_INDEX')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.ticket.statuses.create') }}">ایجاد وضعیت</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>نام</th>
                                <th>رنگ</th>
                                <th>توضیحات</th>
                                <th>ترتیب</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($statuses->isNotEmpty())
                                @foreach($statuses as $status)
                                    <tr>
                                        <td>{{ $status->id }}</td>
                                        <td>{{ $status->name }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $status->color }}">
                                                {{ $status->color }}
                                            </span>
                                        </td>
                                        <td>{{ $status->description }}</td>
                                        <td>{{ $status->order }}</td>
                                        <td>
                                            <a href="{{ route('admin.ticket.statuses.edit', $status->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
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
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('admin.partial.datatable_offline')
@endsection