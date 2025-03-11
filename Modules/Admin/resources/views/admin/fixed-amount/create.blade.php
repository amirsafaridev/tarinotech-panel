@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.fixed-amount.index') }}">مبالغ ثابت</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.admin.fixed-amount.store') }}">
                        @csrf

                        <x-admin.input identify="basic_rights" title="پایه حقوق(مبلغ کل واحد پایه)"/>
                            <x-admin.input identify="right_to_housing" title="حق مسکن"/>
                            <x-admin.input identify="right_to_marry" title="حق تأهل"/>
                            <x-admin.input identify="childrens_right" title="حق اولاد"/>
                            <x-admin.input identify="right_to_eat_and_drink" title="حق خوار و بار"/>
                            <x-admin.input identify="employer_insurance" title="بیمه سهم کارفرما(حضوری)"/>
                            <x-admin.input identify="personnel_insurance" title="بیمه سهم پرسنل(حضوری)"/>
                            <x-admin.input identify="employer_insurance_remote" title="بیمه سهم کارفرما(دورکاری)"/>
                            <x-admin.input identify="personnel_insurance_remote" title="بیمه سهم پرسنل(دورکاری)"/>
                            <x-admin.input
                            identify="date"
                            title="تاریخ"
                            :is-date-picker="true"
                            :old="request('date')"
                    />
                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@include('admin.partial.loader.script',['load'=>[
    \App\Enums\Assets\ScriptLoader::Datepicker(),
]])
@include('admin.partial.request')


    @include('admin.partial.request')
    @include('admin.partial.script.global')
  
    <script>
        $(document).ready(function () {
            jalaliDatepicker.startWatch();

            makeInputPrice($('#basic_rights'));
            makeInputPrice($('#right_to_housing'));
            makeInputPrice($('#right_to_marry'));
            makeInputPrice($('#childrens_right'));
            makeInputPrice($('#right_to_eat_and_drink'));
            makeInputPrice($('#employer_insurance'));
            makeInputPrice($('#personnel_insurance'));
            makeInputPrice($('#employer_insurance_remote'));
            makeInputPrice($('#personnel_insurance_remote'));
            activeParentUl('{{ route('admin.admin.fixed-amount.index') }}');
        })
    </script>
@endsection
