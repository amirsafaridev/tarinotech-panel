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
        <h1 class="page-title">پرسنل - ایجاد</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ $routeStore }}">
        <div class="col-12">
            @include('admin.partial.message')
            @csrf
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('admin.admin.cards.auth')

            @include('admin.admin.cards.profile')

            @include('admin.admin.cards.company')

        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.request')

    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::CKEditor(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
        \App\Enums\Assets\ScriptLoader::InputMask(),
    ]])
    @include('admin.partial.script.global')
    @include('admin.partial.script.mask')
    @include('admin.partial.ckeditor')
    @include('admin.admin.script.share')
@endsection
