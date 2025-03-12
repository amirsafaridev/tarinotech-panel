@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Alert(),
        \App\Enums\Assets\StyleLoader::Datepicker(),

    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.variable-amount.index') }}">مبالغ متغیر</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.admin.variable-amount.update',$variableAmount->id) }}">
                        @csrf
                        @method('PATCH')
                            <x-admin.input identify="base_units_count" title="تعداد واحد پایه P" :old="number_format($variableAmount->base_units_count)"/>
                            <x-admin.input identify="extra_units_amount" title="قیمت واحد اکسترا E" :old="number_format($variableAmount->extra_units_amount)"/>
                            <x-admin.input identify="performance_amount" title="مبلغ عملکرد ویژه" :old="number_format($variableAmount->performance_amount)"/>
                            <x-admin.input identify="reward_basis" title="مبنای پاداش بهره وری":old="number_format($variableAmount->reward_basis)"/>
                                <x-admin.select-model identify="job_title_id"
                              title="سمت شغلی"
                              value="title"
                              key="id"
                              :old="$variableAmount->job_title_id"
                              :items="$jobTitles"/>
                                <x-admin.input
                                identify="from_date"
                                title="تاریخ"
                                :is-date-picker="true"
                                :old="$variableAmount->date"
                        />
                                <x-admin.button title="ویرایش"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteItem" action="{{ route('admin.admin.variable-amount.destroy',$variableAmount->id) }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.script.global')

    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),

    ]])
    <script>
        $(document).ready(function () {
            jalaliDatepicker.startWatch();
            makeInputPrice($('#base_units_count'));
            makeInputPrice($('#extra_units_amount'));
            makeInputPrice($('#performance_amount'));
            makeInputPrice($('#reward_basis'));
           
            activeParentUl('{{ route('admin.admin.variable-amount.index') }}');
        })
    </script>
@endsection
