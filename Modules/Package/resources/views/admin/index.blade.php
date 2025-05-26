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
                    <h3 class="card-title">پکیج ها</h3>
                    @can('ADMIN_PACKAGE_CREATE')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.package.create') }}">ایجاد پکیج</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>نوع</th>
                                <th>عنوان</th>
                                <th>میزان واحد اصلی</th>

                                <th>قیمت</th>
                                <th>تاریخ شروع</th>
                                <th>تاریخ پایان</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($packages->isNotEmpty())
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $package->id }}</td>
                                        <td>{{ $package->type->path }}</td>
                                        <td>{{ $package->title }}</td>
                                        <td>{{ $package->main_unit }}</td>

                                        @if($package->finalPrice)
                                            <td>{{  number_format($package->finalPrice->price) }}</td>
                                            <td>
                                                {{ verta($package->finalPrice->start_at)->format(formatJalaliDate()) }}
                                            </td>
                                            <td>
                                                @if(is_null($package->finalPrice->end_at))
                                                    <span>تا هم اکنون</span>
                                                @else
                                                    {{ verta($package->finalPrice->end_at)->format(formatJalaliDate()) }}
                                                @endif
                                            </td>
                                        @else
                                            <td>0</td>
                                            <td>ثبت نشده</td>
                                            <td>ثبت نشده</td>
                                        @endif

                                        <td>
                                            <a href="{{ route('admin.package.edit',$package->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
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
