@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Select2(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.support.index') }}">پشتیبانی</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.support.group.store') }}">
                        @csrf

                        <x-admin.select-model
                                title="انتخاب پروژه"
                                identify="project_id"
                                key="id"
                                value="optionTitle"
                                :items="$projects"
                        />

                        <x-admin.select-model
                                title="انتخاب پرسنل"
                                identify="admin_id[]"
                                value="optionTitle"
                                key="id"
                                :multiple="true"
                                :items="$admins"
                        />

                        <x-admin.input identify="logo" title="تصویر" type="file"/>

                        <x-admin.input identify="title" title="عنوان"/>


                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.request')
    <script>
        const projectId = $('#project_id');
        const adminIds = $('#admin_id');
        $(document).ready(function () {
            activeParentUl('{{ route('admin.support.index') }}');
            projectId.select2();
            adminIds.select2();
        })
    </script>
@endsection
