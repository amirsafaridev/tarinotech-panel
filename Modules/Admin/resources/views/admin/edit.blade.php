@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Datepicker(),
        \App\Enums\Assets\StyleLoader::Select2(),
        \App\Enums\Assets\StyleLoader::Alert(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" enctype="multipart/form-data" method="post" action="{{ route('admin.admin.update',$admin->id) }}">

        <div class="col-12">
            @include('admin.partial.message')
            @csrf
            @method('PUT')
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">

            @include('admin::admin.cards.auth',['admin'=>$admin])

            @include('admin::admin.cards.profile',['admin'=>$admin])

            @include('admin::admin.cards.company',['admin'=>$admin])

        </div>

    </form>

    <form id="deleteItem" action="{{ route('admin.admin.destroy',$admin->id) }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
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
    @include('admin::admin.script.share')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.index') }}');
        })
    </script>
@endsection
