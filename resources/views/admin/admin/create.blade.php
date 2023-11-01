@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
       \App\Enums\Assets\StyleLoader::Select2(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پرسنل - ایجاد</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ $routeStore }}">
        <div class="col-12">
            @include('admin.partial.message')
            @csrf
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات ورود</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse"
                           data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="email" title="پست الکترونیکی"/>

                    <x-admin.input identify="mobile" title="شماره همراه"/>

                    <x-admin.select-model multiple="multiple"
                                          identify="role[]"
                                          title="سطح دسترسی"
                                          :items="$roles"
                                          key="id"
                                          value="name"/>

                    <div class="alert alert-success">
                        <p>گذرواژه برای پست الکترونیکی و شماره همراه ارسال خواهد شد.</p>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات پروفایل</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse"
                           data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <x-admin.input identify="avatar" title="تصویر" type="file"/>

                    <x-admin.input identify="first_name" title="نام"/>

                    <x-admin.input identify="last_name" title="نام خانوادگی"/>

                    <x-admin.input identify="dob" title="تاریخ تولد"/>

                    <x-admin.textarea identify="resume" title="رزومه"/>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات شرکتی</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse"
                           data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="mobile_company" title="شماره همراه شرکتی" />

                    <x-admin.input identify="number_company" title="شماره داخلی"/>

                    <x-admin.input identify="start_cooperation" title="شروع همکاری"/>

                    <x-admin.input identify="start_last_contract" title="تاریخ شروع آخرین قرارداد"/>

                    <x-admin.input identify="end_last_contract" title="تاریج پایان آخرین قرارداد"/>

                    <x-admin.textarea identify="description" title="توضیحات"/>

                    <x-admin.checkbox identify="has_access" description="بلاک شود"/>

                    <x-admin.button-submit/>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::CKEditor(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.ckeditor')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('description');

            const dataPickerConfig = {
                format: 'YYYY/MM/DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };

            $('#dob').persianDatepicker(dataPickerConfig);
            $('#start_cooperation').persianDatepicker(dataPickerConfig);
            $('#start_last_contract').persianDatepicker(dataPickerConfig);
            $('#end_last_contract').persianDatepicker(dataPickerConfig);
            $('#role').select2();
        })
    </script>
@endsection
