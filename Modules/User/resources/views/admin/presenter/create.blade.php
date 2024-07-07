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
                <li class="breadcrumb-item"><a href="{{ route('admin.presenter.index') }}">نمایندگان</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.presenter.store') }}">
                        @csrf

                        <x-admin.input identify="mobile" :title="trans('fields.admin.mobile')" />

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="first_name" title="نام" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="last_name" title="نام خانوادگی" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="email" title="پست الکترونیکی" type="email" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="tel" title="تلفن ثابت" />
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="project_id" class="form-label">انتخاب پروژه</label>
                            <select  class="form-control" name="project_ids[]" id="project_id" multiple>
                                <option value="">انتخاب پروژه</option>
                            </select>
                        </div>


                        <x-admin.checkbox identify="is_block" description="دسترسی داشته باشد"  />

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    <script>
        $(document).ready(function () {
            makeSelect2Remote($('#project_id'),'{{ route('admin.ajax.project.remote-select') }}',['title']);
            activeParentUl('{{ route('admin.presenter.index') }}');
        })
    </script>
@endsection
