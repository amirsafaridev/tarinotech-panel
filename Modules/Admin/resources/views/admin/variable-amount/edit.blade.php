@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Alert(),
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
                            <x-admin.input identify="title" title="سمت" :old="$variableAmount->title"/>
                            <x-admin.input identify="base_units_count" title="تعداد واحد پایه P" :old="$variableAmount->base_units_count"/>
                            <x-admin.input identify="extra_units_amount" title="قیمت واحد اکسترا E" :old="$variableAmount->extra_units_amount"/>
                            <x-admin.input identify="performance_amount" title="مبلغ عملکرد ویژه" :old="$variableAmount->performance_amount"/>
                            <x-admin.input identify="reward_basis" title="مبنای پاداش بهره وری":old="$variableAmount->reward_basis"/>
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
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.variable-amount.index') }}');
        })
    </script>
@endsection
