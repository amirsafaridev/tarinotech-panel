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
                    <h3 class="card-title">پاداش</h3>
                    @can('ADMIN_ADMIN_BONUSES_CREATE')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.admin.bonuses.create') }}">
                            ثبت پاداش</a>
                    @endcan

                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>پرسنل</th>
                                    <th>مبلغ</th>
                                    <th>علت</th>
                                    <th>توضیحات</th>

                                    <th>تاریخ</th>

                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($bonuses->isNotEmpty())
                                    @foreach ($bonuses as $bonus)
                                        <tr>

                                            <td>{{ $bonus->id }}</td>


                                            <td>{{ $bonus->user->fullname }}
                                            </td>
                                            <td>{{ number_format($bonus->price) }}</td>
                                            <td>{{ $bonus->reason->title }}</td>

                                            <td>{{ $bonus->description }}</td>
                                            <td>{{ $bonus->date }}</td>

                                            <td>
                                                @can('ADMIN_ADMIN_BONUSES_EDIT')
                                                    <a href="{{ route('admin.admin.bonuses.edit', $bonus->id) }}"
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
