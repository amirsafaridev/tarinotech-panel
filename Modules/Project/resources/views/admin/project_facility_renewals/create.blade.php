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
                <li class="breadcrumb-item"><a href="{{ route('admin.project.renewal.index') }}">لیست تمدید ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.renewal.show',$projectRenewal->id) }}">تمدید سالیانه</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.project.renewal.facility.store',['project_renewal_id'=>$projectRenewal->id]) }}">
                        @csrf

                        <x-admin.select-model
                            identify="facilities[]"
                            title="ویژگی‌ها"
                            key="id"
                            value="title"
                            :multiple="true"
                            :with-option="false"
                            :items="$facilities"/>

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
        $(document).ready(function (){
            activeParentUl('{{ route('admin.project.renewal.index') }}');

            const selectFacilities = $('#facilities');
            selectFacilities.select2();
        });
    </script>
@endsection
