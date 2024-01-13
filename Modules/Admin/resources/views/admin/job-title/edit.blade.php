@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Alert(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.job-title.index') }}">عنوان شغلی</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.admin.job-title.update',$jobTitle->id) }}">
                        @csrf
                        @method('PATCH')
                        <x-admin.input identify="title" title="عنوان" :old="$jobTitle->title"/>

                        <x-admin.button-submit title="ویرایش"/>

                        <x-admin.button-delete/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteItem" action="{{ route('admin.admin.job-title.destroy',$jobTitle->id) }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.job-title.index') }}');
        })
    </script>
@endsection
