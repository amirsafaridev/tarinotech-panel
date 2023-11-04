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
        <h1 class="page-title">تقویم تعطیلات - جدید</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.free-day.index') }}">تقویم تعطیلات</a></li>
                <li class="breadcrumb-item active">جدید</li>
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
                        <x-admin.input identify="title" title="عنوان"/>
                        <x-admin.input identify="free_at"
                                       title="تاریخ"
                                       :is-date-picker="true"/>
                        <x-admin.button-submit/>
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
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.free-day.index') }}');
            jalaliDatepicker.startWatch();
        })
    </script>
@endsection
