@php use App\Enums\Assets\StyleLoader; @endphp
@php use Modules\Survey\app\Enums\Database\AuthTypeEnum; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       StyleLoader::Toast(),
       StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسش نامه ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.survey.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.input identify="title" title="عنوان پرسش نامه"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.textarea identify="description" title="توضیحات"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-admin.textarea identify="thank_you_message" title="متن انتهایی پرسشنامه" placeholder="متنی که بعد از تکمیل پرسشنامه به کاربر نمایش داده می‌شود"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input identify="start_date"
                                               title="تاریخ شروع"
                                               :is-date-picker="true"/>
                            </div>
                            <div class="col-md-6">
                                <x-admin.input identify="end_date"
                                               title="تاریخ پایان"
                                               :is-date-picker="true"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.checkbox identify="is_active" description="فعال"/>
                            </div>
                            <div class="col-md-6">
                                <x-admin.checkbox identify="has_meta" description="دارای متا"/>
                            </div>
                            <div class="col-md-6">
                                <x-admin.checkbox identify="requires_auth" description="نیاز به احراز هویت"/>
                            </div>
                        </div>

                        <div class="row auth-guard-container d-none">
                            <div class="col-md-12">
                                <x-admin.select-enum
                                    identify="auth_guard"
                                    title="انتخاب گارد احراز هویت"
                                    :old="request('auth_guard')"
                                    :is-small="false"
                                    :enum-class="AuthTypeEnum::class"
                                />
                            </div>
                        </div>

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Datepicker(),
    ]])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');
            jalaliDatepicker.startWatch();

            $('#requires_auth').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.auth-guard-container').removeClass('d-none');
                } else {
                    $('.auth-guard-container').addClass('d-none');
                }
            });
        })
    </script>
@endsection
