@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ trans('panel.admin.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('panel.admin.show') }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <span class="bold">اهداف فردی فروش</span>
                </div>
                <div class="card-body">
                    <p class="card-text mb-3">تعریف اهداف فردی فروش برای پرسنل</p>
                    <a href="{{ route('admin.admin.goal',$admin->id) }}" class="btn btn-success">ثبت</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <span class="bold">ورود ها</span>
                </div>
                <div class="card-body">
                    <p class="card-text mb-3">نمایش تاریخ ورود و خروج ها</p>
                    <a href="{{ route('admin.report.login',['user-type'=>'admin','user-id'=>$admin->id]) }}" class="btn btn-success">گزارش</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-2">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td>آواتار</td>
                                <td>
                                    @if($admin->avatar)
                                        <img class="admin-avatar" src="{{ asset($admin->avatar) }}" alt="{{ $admin->first_name }} {{ $admin->last_name }}">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>شناسه</td>
                                <td><a href="{{ route('admin.admin.edit',$admin->id) }}">{{ $admin->id }}</a></td>
                            </tr>
                            <tr>
                                <td>پست الکترونیک</td>
                                <td><a href="mailto:{{ $admin->email }}">{{ $admin->email }}</a></td>
                            </tr>
                            <tr>
                                <td>شماره همراه</td>
                                <td><a href="tel:{{ $admin->mobile }}">{{ $admin->mobile }}</a></td>
                            </tr>
                            <tr>
                                <td>نام</td>
                                <td>{{ $admin->first_name }}</td>
                            </tr>
                            <tr>
                                <td>نام خانوادگی</td>
                                <td>{{ $admin->last_name }}</td>
                            </tr>

                            @if($admin->dob)
                                <tr>
                                    <td>تاریخ تولد</td>
                                    <td>{{ $admin->dob->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif

                            @if($admin->start_cooperation)
                                <tr>
                                    <td>تاریخ شروع همکاری</td>
                                    <td>{{ $admin->start_cooperation->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif

                            @if($admin->start_last_contract)
                                <tr>
                                    <td>شروع آخرین قرارداد</td>
                                    <td>{{ $admin->start_last_contract->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif


                            @if($admin->end_last_contract)
                                <tr>
                                    <td>پایان آخرین قرارداد</td>
                                    <td>{{ $admin->end_last_contract->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td>رزومه</td>
                                <td>{{ $admin->resume }}</td>
                            </tr>

                            <tr>
                                <td>توضیحات</td>
                                <td>{{ $admin->description }}</td>
                            </tr>

                            <tr>
                                <td>دسترسی</td>
                                <td>@include('admin.partial.bool_badge',['value'=>$admin->has_access])</td>
                            </tr>

                            <tr>
                                <td>سطح دسترسی</td>
                                <td>{{ $admin->roles()->get()->implode('name',',') }}</td>
                            </tr>

                            @if($admin->latestLogin)
                                <tr>
                                    <td>آخرین ورود</td>
                                    <td>{{ $admin->latestLogin->login_at->toJalali()->format('d F Y - H:i') }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{$admin->created_at->toJalali()->format('d F Y - H:i')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
