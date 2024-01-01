@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Alert(),
       \App\Enums\Assets\StyleLoader::Select2(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ route('admin.factor.store') }}">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">ایجاد فاکتور</div>
                </div>

                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <div class="forms-sample">
                        @csrf
                        <x-admin.input identify="title" title="عنوان فاکتور"/>

                        <x-admin.select-model
                                title="انتخاب پروژه"
                            identify="project_id"
                            key="id"
                            value="optionTitle"
                            :items="$projects"
                        />

                        <div id="project_info"></div>

                        <x-admin.checkbox identify="custom_customer" description="ثبت دستی مشتری"/>

                        <div id="custom_customer_container" style="display: none" class="p-2 mb-2">
                            <x-admin.select-model
                                    identify="type_id"
                                    title="نوع پروژه"
                                    :items="$types"
                                    :has-choice-option="false"
                                    key="id"
                                    value="title"/>

                            <x-admin.input identify="project_title" title="عنوان پروژه"/>

                            <x-admin.input identify="customer_fullname" title="نام و نام خانوادگی"/>

                            <x-admin.input identify="customer_mobile" title="شماره موبایل"/>
                        </div>

                        <x-admin.input identify="expired_at"
                                       title="تاریخ انقضاء"
                                       :is-date-picker="true"
                                       old="{{ verta(now()->addDays(3))->format('Y/m/d') }}"/>

                        <x-admin.button-submit/>

                        <button id="btn_add_item" class="btn btn-success" type="button">افزودن آیتم</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="factor_item_container" class="col-12"></div>
    </form>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),

    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('factor::admin.part.script')
@endsection
