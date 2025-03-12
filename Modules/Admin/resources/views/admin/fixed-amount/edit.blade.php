@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [
        \App\Enums\Assets\StyleLoader::Toast(), 
        \App\Enums\Assets\StyleLoader::Alert(), 
        \App\Enums\Assets\StyleLoader::Datepicker(),
        ],
    ])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.fixed-amount.index') }}">مبالغ ثابت</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                        action="{{ route('admin.admin.fixed-amount.update', $fixedAmount->id) }}">
                        @csrf
                        @method('PATCH')
                        <x-admin.input identify="basic_rights" title="پایه حقوق(مبلغ کل واحد پایه)" :old="number_format($fixedAmount->basic_rights)" />
                        <x-admin.input identify="right_to_housing" title="حق مسکن" :old="number_format($fixedAmount->right_to_housing)" />
                        <x-admin.input identify="right_to_marry" title="حق تأهل" :old="number_format($fixedAmount->right_to_marry)" />
                        <x-admin.input identify="childrens_right" title="حق اولاد" :old="number_format($fixedAmount->childrens_right)" />
                        <x-admin.input identify="right_to_eat_and_drink" title="حق خوار و بار" :old="number_format($fixedAmount->right_to_eat_and_drink)" />
                        <x-admin.input identify="employer_insurance" title="بیمه سهم کارفرما(حضوری)" :old="number_format($fixedAmount->employer_insurance)" />
                        <x-admin.input identify="personnel_insurance" title="بیمه سهم پرسنل(حضوری)" :old="number_format($fixedAmount->personnel_insurance)" />
                        <x-admin.input identify="employer_insurance_remote" title="بیمه سهم کارفرما(دورکاری)"
                            :old="number_format($fixedAmount->employer_insurance_remote)" />
                        <x-admin.input identify="personnel_insurance_remote" title="بیمه سهم پرسنل(دورکاری)"
                            :old="number_format($fixedAmount->personnel_insurance_remote)" />
                            <x-admin.input
                            identify="date"
                            title="تاریخ"
                            :is-date-picker="true"
                            :old="$fixedAmount->date"
                    />
                        <x-admin.button title="ویرایش" />

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                            on-click="confirmDelete()" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteItem" action="{{ route('admin.admin.fixed-amount.destroy', $fixedAmount->id) }}" method="post"
        class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.loader.script', ['load' => [    \App\Enums\Assets\ScriptLoader::Datepicker(),
\App\Enums\Assets\ScriptLoader::Alert()]])
    @include('admin.partial.script.global')

    <script>
        $(document).ready(function() {
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
