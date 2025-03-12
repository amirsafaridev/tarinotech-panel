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
                    <h3 class="card-title">مساعده ها</h3>

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
                                    <th>وضعیت</th>

                                    <th>تاریخ</th>

                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($personnelAssistances->isNotEmpty())
                                    @foreach ($personnelAssistances as $personnelAssistance)
                                        <tr>
                                            <td>{{ $personnelAssistance->id }}</td>

                                            <td>{{ $personnelAssistance->user->fullname }}
                                            </td>

                                            <td>{{ number_format($personnelAssistance->price) }}</td>
                                            <td>{{ $personnelAssistance->description }}</td>
                                            <td>
                                                @if ($personnelAssistance->status === 0)
                                                    <span class="badge bg-warning">
                                                        در انتظار تایید
                                                    </span>
                                                @elseif ($personnelAssistance->status === 1)
                                                    <span class="badge bg-success">
                                                        تایید شده
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        رد شده
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $personnelAssistance->date }}</td>

                                            <td>

                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        {{ __('panel.action.manage') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.personnel.personnel-assistance.edit', $personnelAssistance->id) }}">{{ __('panel.action.edit') }}</a>
                                                        @if ($personnelAssistance->status === 0)
                                                            @can('ADMIN_PERSONNEL_PERSONNEL_ASSISTANCE_APPROVED')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.personnel.personnel-assistance.approved', $personnelAssistance->id) }}">{{ __('panel.action.approve') }}</a>
                                                            @endcan

                                                            @can('ADMIN_PERSONNEL_PERSONNEL_ASSISTANCE_CANCELED')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.personnel.personnel-assistance.canceled', $personnelAssistance->id) }}">{{ __('panel.action.cancel') }}</a>
                                                            @endcan
                                                        @endif

                                                    </div>
                                                </div>
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
