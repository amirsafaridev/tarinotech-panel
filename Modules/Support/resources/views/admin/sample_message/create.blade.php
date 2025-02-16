@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.support.sample-message.index') }}">پیام های آماده</a></li>
                <li class="breadcrumb-item active">جدید</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.support.sample-message.store') }}">
                        @csrf
                        <x-admin.input identify="title" title="عنوان"/>
                        <x-admin.textarea identify="message" rows="10" title="متن پیام"/>
                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::CKEditor(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.ckeditor')
    <script>
        $(document).ready(function () {
            //CKEDITOR.replace( 'message'); #TODO FIX
            activeParentUl('{{ route('admin.support.sample-message.index') }}');
        })
    </script>
@endsection
