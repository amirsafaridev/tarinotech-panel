@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Select2(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">فاکتور - ایجاد</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
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

                        <div class="mb-3">
                            <label for="project_id" class="form-label">انتخاب پروژه</label>
                            <select  class="form-control" name="project_ids[]" id="project_id" multiple>
                                <option value="">انتخاب پروژه</option>
                            </select>
                        </div>

                        <x-admin.input identify="title" title="عنوان فاکتور" />

                        <x-admin.input identify="expired_at" title="تاریخ انقضاء" old="{{ verta(now()->addDays(3))->format('Y/m/d') }}" />

                        <x-admin.button-submit/>

                        <button class="btn btn-success" type="button">افزودن ایتم</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.share-script')
    <script>
        $(document).ready(function () {

            makeSelect2Remote($('#project_id'),'{{ route('admin.ajax.select2.project') }}',['domain']);

            const dataPickerConfig = {
                format: 'YYYY/MM/DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };
            $('#expired_at').persianDatepicker(dataPickerConfig);
        })
    </script>
@endsection
