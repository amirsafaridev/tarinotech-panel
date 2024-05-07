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
                <li class="breadcrumb-item"><a href="{{ route('admin.content.slider.index') }}">اسلایدر ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.content.slider.store') }}">
                        @csrf
                        <x-admin.input identify="title" title="عنوان"/>

                        <x-admin.input identify="link" title="لینک"/>

                        <x-admin.input type="file" identify="photo" title="تصویر"/>

                        <x-admin.input identify="published_at"
                                       title="تاریخ انتشار"
                                       :is-date-picker="true"/>

                        <x-admin.input identify="archived_at"
                                       title="تاریخ آرشیو"
                                       :is-date-picker="true"/>

                        <x-admin.checkbox identify="status"
                                       description="فعال؟"
                                       />

                        <x-admin.textarea identify="description" title="متن"/>
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
            activeParentUl('{{ route('admin.content.slider.index') }}');
        })
    </script>
    @include('content::admin.slider.script')
@endsection
