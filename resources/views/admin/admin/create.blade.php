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
        <h1 class="page-title">{{ trans('panel.admin.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('panel.admin.create') }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ $routeStore }}">
                        @csrf

                        <x-admin.input identify="avatar" :title="trans('fields.admin.avatar')" type="file" />

                        <x-admin.input identify="email" :title="trans('fields.admin.email')" type="text" />

                        <x-admin.input identify="mobile" :title="trans('fields.admin.mobile')" type="text" />

                        <x-admin.input identify="first_name" :title="trans('fields.admin.first_name')" type="text" />

                        <x-admin.input identify="last_name" :title="trans('fields.admin.last_name')" type="text" />

                        <x-admin.select-model multiple="multiple" identify="role[]" id="role" :title="trans('fields.admin.role')" :items="$roles" key="id" value="name" />

                        <x-admin.input identify="password" :title="trans('fields.admin.password')" type="password" />

                        <x-admin.input identify="dob" :title="trans('fields.admin.dob')" type="text" />

                        <x-admin.input identify="start_cooperation" :title="trans('fields.admin.start_cooperation')" type="text" />

                        <x-admin.input identify="start_last_contract" :title="trans('fields.admin.start_last_contract')" type="text" />

                        <x-admin.input identify="end_last_contract" :title="trans('fields.admin.end_last_contract')" type="text" />

                        <x-admin.textarea identify="resume" :title="trans('fields.admin.resume')" />

                        <x-admin.textarea identify="description" :title="trans('fields.admin.description')" />

                        <x-admin.checkbox identify="has_access" :description="trans('fields.admin.has_access')"  />
                        
                        <x-admin.button-submit/>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
            CKEDITOR.replace( 'description');
            CKEDITOR.replace( 'resume');

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
