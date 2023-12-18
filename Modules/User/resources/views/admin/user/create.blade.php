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
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">مشتری ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.user.store') }}">
                        @csrf

                        <x-admin.input identify="mobile" :title="trans('fields.admin.mobile')" />

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="first_name" title="نام" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="en_first_name" title="به لاتین" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="last_name" title="نام خانوادگی" />

                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="en_last_name" title="به لاتین" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="father_name" title="نام پدر" />

                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="national_id" title="کد ملی" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="document_id" title="شماره شناسنامه" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="dob" title="تاریخ تولد" :is-date-picker="true" />
                            </div>
                        </div>

                        <x-admin.select-enum identify="person_type" title="نوع شخص" :enum-class="\Modules\User\app\Enums\PersonType::class"/>

                        <div id="company_container" class="d-none">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="company_name" title="نام شرکت" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <x-admin.select-enum identify="company_type" title="نوع شرکت" :enum-class="\App\Enums\Database\Company\CompanyType::class"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="company_identify" title="شناسه ملی شرکت" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="company_register_id" title="شماره ثبت شرکت" />
                                </div>
                            </div>
                        </div>

                        <x-admin.textarea identify="address" rows="5" title="آدرس" />

                        <x-admin.input identify="postal_code" title="کدپستی" />


                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="email" title="پست الکترونیکی" type="email" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="tel" title="تلفن ثابت" />
                            </div>
                        </div>

                        <x-admin.select-enum identify="irnic_status" title="شناسه ایرنیک" :enum-class="\Modules\User\app\Enums\IrnicStatus::class"/>

                        <div id="irnic_container">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="irnic_identify" title="شناسه ایرنیک" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="irnic_password" title="رمز عبور ایرنیک" />
                                </div>
                            </div>
                        </div>

                        <x-admin.input identify="avatar" :title="trans('fields.admin.avatar')" type="file" />

                        <x-admin.input identify="national_photo" title="تصویر کارت ملی" type="file" />

                        <x-admin.checkbox identify="official_bill" description="درخواست فاکتور رسمی"  />

                        <x-admin.button-submit/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Alert(),
       \App\Enums\Assets\ScriptLoader::Datepicker(),
       \App\Enums\Assets\ScriptLoader::Select2(),
       \App\Enums\Assets\ScriptLoader::InputMask(),
   ]])

    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.script.mask')

    @include('admin.partial.ckeditor')
    @include('user::admin.user.script.share')

    <script>
        $(document).ready(function () {
        })
    </script>
@endsection
