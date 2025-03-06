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
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.variable-amount.index') }}">مبالغ متغیر</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.admin.variable-amount.store') }}">
                        @csrf

                        <x-admin.input identify="title" title="سمت"/>
                            <x-admin.input identify="base_units_count" title="تعداد واحد پایه P"/>
                            <x-admin.input identify="extra_units_amount" title="قیمت واحد اکسترا E"/>
                            <x-admin.input identify="performance_amount" title="مبلغ عملکرد ویژه"/>
                            <x-admin.input identify="reward_basis" title="مبنای پاداش بهره وری"/>

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.variable-amount.index') }}');
        })
    </script>
@endsection
