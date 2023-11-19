@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" enctype="multipart/form-data" method="post" action="{{ route('admin.admin.password.update',$admin->id) }}">
                        @csrf
                        @method('PATCH')

                        <table class="table table-hover mb-4">
                            <tbody>
                            <tr>
                                <td>پست الکترونیک</td>
                                <td><a href="mailto:{{ $admin->email }}">{{ $admin->email }}</a></td>

                                <td>شماره همراه</td>
                                <td><a href="tel:{{ $admin->mobile }}">{{ $admin->mobile }}</a></td>
                            </tr>

                            <tr>
                                <td>شماره همراه شرکتی</td>
                                <td>
                                    @if($admin->mobile_company)
                                        <a href="tel:{{ $admin->mobile_company }}">{{ $admin->mobile_company }}</a>
                                    @endif
                                </td>

                                <td>شماره داخلی</td>
                                <td>
                                    @if($admin->number_company)
                                        <a href="tel:{{ $admin->number_company }}">{{ $admin->number_company }}</a>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>نام</td>
                                <td>{{ $admin->first_name }}</td>

                                <td>نام خانوادگی</td>
                                <td>{{ $admin->last_name }}</td>
                            </tr>

                            <tr>
                                <td>دسترسی</td>
                                <td>@include('admin.partial.bool_badge',['value'=>$admin->has_access])</td>
                                <td>سطح دسترسی</td>
                                <td>{{ $admin->roles()->get()->implode('name',',') }}</td>
                            </tr>

                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{$admin->created_at->toJalali()->format(formatJalaliDateTime())}}</td>

                                <td>آخرین ورود</td>
                                <td>
                                    @if($admin->latestLogin)
                                        {{ $admin->latestLogin->login_at->toJalali()->format(formatJalaliDateTime()) }}
                                    @else
                                        <span>بدون ورود</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <x-admin.input identify="password" title="گذرواژه جدید" type="password"/>

                        <x-admin.input identify="password_rep" title="تکرار گذرواژه" type="password"/>

                        <x-admin.button-submit title="به روز رسانی"/>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.loader.script',['load'=>[
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.index') }}');
        })
    </script>
@endsection
