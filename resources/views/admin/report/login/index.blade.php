@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">گزارش ورود</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">گزارش ورود</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.partial.message')
                    <form method="get" action="{{ route('admin.report.login') }}">
                        <div class="row mb-3">

                            <div class="col-12 col-md-3">
                                <div>
                                    <label for="user-type" class="form-label">نوع کاربر</label>
                                    <select  class="form-control" name="user-type" id="user-type">
                                        <option value="admin">پرسنل</option>
                                        <option value="user">کاربر</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div>
                                    <label for="user-id" class="form-label">انتخاب کاربر</label>
                                    <select  class="form-control" name="user-id" id="user-id">
                                        <option value="">همه کاربران</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div>
                                    <label for="sort" class="form-label">مرتب سازی</label>
                                    <select  class="form-control" name="sort" id="sort">
                                        <option value="date|desc">تاریخ - صعودی</option>
                                        <option value="date|asc">تاریخ - نزولی</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <x-admin.input identify="start_at" title="از تاریخ" type="text" />
                            </div>

                            <div class="col-12 col-md-3">
                                <x-admin.input identify="end_at" title="تا تاریخ" type="text" />
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 col-md-3">
                                <button class="btn btn-md btn-success" name="action" value="filter">اعمال فیلتر</button>
                                <button class="btn btn-md btn-primary" name="action" value="excel">خروجی اکسل</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">نتایج جستجو</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                            <thead>
                            <tr>
                                <th>نوع کاربر</th>
                                <th>شناسه ورود</th>
                                <th>تاریخ ورود</th>
                                <th>IP</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($logins as $login)
                                    <tr>
                                        <td>{{ $login->user_type }}</td>
                                        <td>{{ $login->user_id }}</td>
                                        <td>{{ $login->login_at->toJalali()->format('d F Y - H:i') }}</td>
                                        <td>{{ $login->ip }}</td>
                                        <td>
                                            <a class="btn btn-success btn-sm" href="{{ route('admin.report.login-show',$login->id) }}">نمایش</a>
                                        </td>
                                    </tr>
                                @endforeach
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
       \App\Enums\Assets\ScriptLoader::Datepicker(),
   ]])
    <script>
        $(document).ready(function (){
            const dataPickerConfig = {
                format: 'YYYY-MM-DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };

            $('#start_at').persianDatepicker(dataPickerConfig);
            $('#end_at').persianDatepicker(dataPickerConfig);
        })
    </script>
@endsection
